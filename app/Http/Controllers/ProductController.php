<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ColorProduct;
use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        $banners = Banner::where('status', true)->get();
        $products = Product::all();
        $topDiscountedProducts = Product::whereNotNull('discount')
        ->orderByRaw('(discount / price) DESC') 
        ->limit(12)
        ->get();

        $laptops =  Product::where('category_id', 3)->get();
        $gamingMice =  Product::where('category_id', 2)->get();
        $gamingKeyboards =  Product::where('category_id', 1)->get();
        $headphones =  Product::where('category_id', 4)->get();

        return view('product.index', compact('products', 'laptops', 'gamingMice', 'gamingKeyboards', 'headphones', 'topDiscountedProducts', 'banners'));
    }

    public function list(Request $request)
{
    $brandId = $request->input('brand');
    $categoryId = $request->input('cate');
    $loadType = $request->input('load_type');

    $query = Product::with(['category', 'brand'])
        ->join('category', 'products.category_id', '=', 'category.id')
        ->join('brand', 'products.brand_id', '=', 'brand.id')
        ->select('products.*', 'brand.name as brand_name', 'category.name as cate_name');

    if ($brandId && $brandId !== 'all') {
        $query->where('products.brand_id', $brandId);
    }

    if ($categoryId && $categoryId !== 'all') {
        $query->where('products.category_id', $categoryId);
    }


    if ($loadType) {
        switch ($loadType) {
            case 'new':
                $query->orderBy('products.created_at', 'desc');
                break;
            case 'price_up':
                $query->orderBy('products.price', 'asc');
                break;
            case 'price_down':
                $query->orderBy('products.price', 'desc');
                break;
        }
    }

    $products = $query->paginate(12);

    $brands = Brand::select('id', 'name')->get();
    $categories = Category::select('id', 'name')->get();

    return view('product.list', compact('products', 'brands', 'categories'));
}




public function show($id, Request $request)
{
    $product = Product::findOrFail($id);
    
    // Tăng lượt xem (views)
    $product->increment('views');

    $comments = Comment::where('product_id', $id)->get();
    $colorPro = $request->query('color') ? ColorProduct::find($request->query('color')) : null;

    $relatedProducts = Product::where('category_id', $product->category_id)
        ->where('id', '!=', $id)
        ->limit(3)
        ->get();

    $price_format = number_format($product->price, 0, ',', '.');
    $discount_format = $product->discount ? number_format($product->price * (1 - $product->discount / 100), 0, ',', '.') : $price_format;
    $newprice_format = $discount_format;

    return view('product.show', [
        'product' => $product,
        'colorPro' => $colorPro,
        'relatedProducts' => $relatedProducts,
        'price_format' => $price_format,
        'discount_format' => $discount_format,
        'newprice_format' => $newprice_format,
        'comments' => $comments
    ]);
}
  
public function submitComment(Request $request)
{
    $request->validate([
        'comment' => 'required|string|max:1000', 
        'product_id' => 'required|exists:products,id'
    ]);

    $userId = Auth::check() ? Auth::id() : null;

    if (!$userId) {
        return redirect()->route('product.show', $request->input('product_id'))
                         ->with('error', 'Bạn cần đăng nhập để gửi bình luận!');
    }

    Comment::create([
        'user_id' => $userId,
        'product_id' => $request->input('product_id'),
        'comment' => $request->input('comment'),
    ]);

    return redirect()->route('product.show', $request->input('product_id'))
                     ->with('success', 'Bình luận đã được gửi thành công!');
}
    
    public function about()
    {
        return view('product.about');
    }
    public function payment_guide()
    {
        return view('product.payment_guide');
    }
}