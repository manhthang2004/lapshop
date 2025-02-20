<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Cart;
use App\Models\ColorProduct;
use App\Models\OtherCart;
use App\Models\Product;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $cartItems = OtherCart::whereHas('cart', function ($query) use ($userId) {
            $query->where('id_user', $userId);
        })->get();

        return view('cart.index', compact('cartItems'));
    }
    public function updateQuantity(Request $request)
    {
        $cartItem = OtherCart::where('product_id', $request->product_id)
            ->where('cart_id', $request->cart_id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity = $request->amount;
            $cartItem->save();
            return redirect()->route('cart.index')->with('success', 'Cập nhật số lượng thành công.');
        }

        return redirect()->route('cart.index')->with('error', 'Sản phẩm không tồn tại trong giỏ hàng.');
    }
    
    public function add(Request $request)
{
    if (!Auth::check()) {
        return redirect()->back()->with('error', 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng.');
    }

    $userId = Auth::id();
    $productId = $request->input('product_id');
    $colorProductId = $request->input('color_product_id');
    $quantity = (int) $request->input('quantity');

    if (!$colorProductId) {
        return redirect()->back()->with('error', 'Vui lòng chọn màu sắc.');
    }

    $product = Product::find($productId);
    if (!$product) {
        return redirect()->back()->with('error', 'Sản phẩm không tồn tại.');
    }

    $colorProduct = ColorProduct::find($colorProductId);
    if (!$colorProduct || $colorProduct->quantity < $quantity) {
        return redirect()->back()->with('error', 'Số lượng không đủ.');
    }

    if ($request->input('action') === 'buy_now') {
        session()->put('buy_now', [
            'product_id' => $productId,
            'color_id' => $colorProductId,
            'quantity' => $quantity,
            'price' => $product->price,
        ]);

        return redirect()->route('cart.checkout');
    }

    $cart = Cart::firstOrCreate(['id_user' => $userId]);

    $cartItem = OtherCart::where('cart_id', $cart->id)
        ->where('product_id', $productId)
        ->where('color_id', $colorProductId)
        ->first();

    if ($cartItem) {
        $cartItem->quantity += $quantity;
        $cartItem->save();
    } else {
        OtherCart::create([
            'cart_id' => $cart->id,
            'product_id' => $productId,
            'color_id' => $colorProductId,
            'quantity' => $quantity,
            'price' => $product->price,
        ]);
    }

    return redirect()->route('cart.index')->with('success', 'Sản phẩm đã được thêm vào giỏ hàng.');
}

    
    public function showCheckout()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần phải đăng nhập để thanh toán.');
        }
    
        $cart = Cart::where('id_user', Auth::id())->first();
    
        if (!$cart) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }
    
        $cartItems = OtherCart::where('cart_id', $cart->id)->with('product', 'color')->get();
    
        $totalAmount = $cartItems->sum(function ($item) {
            $productPrice = $item->product->price - $item->product->discount;
            return $productPrice * $item->quantity;
        });
    
        $voucherCode = session('voucher_code', '');
        $voucherDiscount = session('voucher_discount', 0);
    
        $discountedTotal = $totalAmount;
        if ($voucherDiscount > 0) {
            $discountedTotal -= ($totalAmount * $voucherDiscount / 100);
        }
    
        $method = 'cart.checkout.post'; 
    
        return view('cart.checkout', [
            'cartItems' => $cartItems,
            'totalAmount' => $totalAmount,
            'voucherCode' => $voucherCode,
            'voucherDiscount' => $voucherDiscount,
            'discountedTotal' => $discountedTotal,
            'method' => $method, 
        ]);
    }
    

    public function processCheckout(Request $request)
    {
        Log::info($request->all());
    
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'tel' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:255',
            'payment_method' => 'required|string',
            'total_amount' => 'required|numeric',
            'voucher_code' => 'nullable|string'
        ]);
    
        DB::beginTransaction();
    
        try {
            $user = User::find(Auth::id());
            if ($user) {
                $user->update([
                    'tel' => $request->input('tel'),
                    'address' => $request->input('address')
                ]);
            }
    
            $cart = Cart::where('id_user', Auth::id())->first();
            $cartItems = $cart ? OtherCart::where('cart_id', $cart->id)->get() : collect();
    
            if (session()->has('buy_now')) {
                $buyNowItem = session()->get('buy_now');
                $cartItems = collect([
                    (object) [
                        'product_id' => $buyNowItem['product_id'],
                        'color_id' => $buyNowItem['color_id'],
                        'quantity' => $buyNowItem['quantity'],
                        'price' => $buyNowItem['price'],
                    ]
                ]);
                session()->forget('buy_now');
            }
    
            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
            }
    
            $totalAmount = $cartItems->sum(fn($item) => $item->price * $item->quantity);
            $voucher = Voucher::where('code', $request->input('voucher_code'))->first();
            if ($voucher) {
                $totalAmount -= ($totalAmount * $voucher->discount / 100);
            }
    
            $bill = Bill::create([
                'name_user' => $request->input('firstname') . ' ' . $request->input('lastname'),
                'tel_user' => $request->input('tel'),
                'address_user' => $request->input('address'),
                'date' => now(),
                'total' => $totalAmount,
                'payment_name' => $request->input('payment_method'),
                'voucher' => $request->input('voucher_code'),
                'id_status' => 1, 
                'id_user' => Auth::id(),
            ]);
    
            foreach ($cartItems as $item) {
                DB::table('other_bill')->insert([
                    'id_bill' => $bill->id,
                    'id_clp' => $item->color_id,
                    'name_pro' => Product::find($item->product_id)->pro_name ?? 'Không xác định',
                    'color_product' => ColorProduct::find($item->color_id)->color_name ?? 'Không xác định',
                    'price_pro' => $item->price,
                    'quantity_pro' => $item->quantity,
                    'quantity_cart' => $item->quantity,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
    
                $colorProduct = ColorProduct::find($item->color_id);
                if ($colorProduct) {
                    $colorProduct->quantity -= $item->quantity;
                    $colorProduct->save();
                }
            }
    
            if (!session()->has('buy_now')) {
                OtherCart::where('cart_id', $cart->id)->delete();
                $cart->delete();
            }
    
            DB::commit();
    
            return redirect()->route('shipping_process')->with('success', 'Đơn hàng đã được xác nhận.');
    
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi xử lý thanh toán', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi xử lý đơn hàng.');
        }
    }
    

    public function shippingProcess()
    {
        $userId = Auth::id();
        $bills = Bill::with(['voucher', 'otherBills.product', 'status'])
        ->where('id_user', auth()->id())
        ->get();

        $count = $bills->count();
        
        return view('cart.shipping_process', compact('bills', 'count'));
    }
    


    public function completedOrder()
    {
        $userId = Auth::id();
    
        $bills = Bill::where('id_user', $userId)
            ->where('id_status', 3) 
            ->get();
    
        $count = $bills->count();
    
        return view('cart.completed_order', [
            'bills' => $bills,
            'count' => $count,
        ]);
    }
    

    public function cancelledOrder()
    {
        $userId = Auth::id();
    
        $bills = Bill::where('id_user', $userId)
            ->where('id_status', 0) 
            ->get();
    
        $count = $bills->count();
    
        return view('cart.cancelled_order', [
            'bills' => $bills,
            'count' => $count,
        ]);
    }
    
    public function cancelOrder($id)
    {
        $bill = Bill::find($id);
    
        if ($bill) {
            if ($bill->id_status == 1) {
                $bill->id_status = 0;
                $bill->save();
    
                return redirect()->route('shipping_process')->with('success', 'Đơn hàng đã được hủy thành công.');
            }
    
            return redirect()->route('shipping_process')->with('error', 'Đơn hàng không thể hủy.');
        }
    
        return redirect()->route('shipping_process')->with('error', 'Đơn hàng không tồn tại.');
    }
    
    public function applyVoucher(Request $request)
    {
        $validated = $request->validate([
            'voucher_code' => 'required|string|max:255',
        ]);

        $voucher = Voucher::where('code', $request->voucher_code)->first();

        if (!$voucher) {
            return redirect()->route('cart.checkout')->with('error', 'Mã giảm giá không hợp lệ.');
        }

        session([
            'voucher_code' => $voucher->code,
            'voucher_discount' => $voucher->discount,
        ]);

        return redirect()->route('cart.checkout')->with('success', 'Mã giảm giá đã được áp dụng.');
    }



    public function remove($id_cart, $color_id)
    {
        $cartItem = OtherCart::where('cart_id', $id_cart)->where('color_id', $color_id)->first();

        if ($cartItem) {
            $cartItem->delete();
            return redirect()->route('cart.index')->with('success', 'Sản phẩm đã được xóa khỏi giỏ hàng.');
        }

        return redirect()->route('cart.index')->with('error', 'Sản phẩm không tồn tại trong giỏ hàng.');
    }
}
