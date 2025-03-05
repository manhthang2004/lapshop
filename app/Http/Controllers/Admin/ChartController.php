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
        $totalOrders = Bill::count();

        $totalRevenue = Bill::where('id_status', 3)->sum('total');

        $totalProductsSold = DB::table('other_bill')->sum('quantity_pro');

        $totalUsers = User::count();

        $topProducts = DB::table('other_bill')
            ->select('name_pro as name', 
                     DB::raw('SUM(quantity_pro) as total_quantity'), 
                     DB::raw('SUM(price_pro * quantity_pro) as total_revenue'))
            ->groupBy('name_pro')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();

        $mostClickedProducts = Product::orderByDesc('views')
            ->select('pro_name as name', 'views')
            ->limit(10)
            ->get();

        return view('admin.chart.index', compact('totalOrders', 'totalRevenue', 'totalProductsSold', 'totalUsers', 'topProducts', 'mostClickedProducts'));
    }
}