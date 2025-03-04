<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller
{
    public function index() {
        // Tổng số đơn hàng
        $totalOrders = Bill::count();

        // Tổng doanh thu (chỉ tính đơn đã hoàn thành)
        $totalRevenue = Bill::where('id_status', 1)->sum('total');



        // Tổng số sản phẩm đã bán
        $totalProductsSold = DB::table('other_bill')->sum('quantity_pro');

        // Tổng số người dùng (Users)
        $totalUsers = User::count();

        // Lấy top 10 sản phẩm bán chạy nhất
        $topProducts = DB::table('other_bill')
            ->select('name_pro as name', 
                     DB::raw('SUM(quantity_pro) as total_quantity'), 
                     DB::raw('SUM(price_pro * quantity_pro) as total_revenue'))
            ->groupBy('name_pro')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();

        // Lấy top 10 sản phẩm được click nhiều nhất
        $mostClickedProducts = Product::orderByDesc('views')
            ->select('pro_name as name', 'views')
            ->limit(10)
            ->get();

        // Trả dữ liệu về view
        return view('admin.chart.index', compact('totalOrders', 'totalRevenue', 'totalProductsSold', 'totalUsers', 'topProducts', 'mostClickedProducts'));
    }
}