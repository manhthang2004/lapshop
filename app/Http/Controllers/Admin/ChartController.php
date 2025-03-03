<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller
{
    public function index() {
        $orders = Bill::with('status')->orderBy('date', 'desc')->get();
        
        $topProducts = DB::table('other_bill')
            ->select('name_pro as name', DB::raw('SUM(quantity_pro) as total_quantity'), DB::raw('SUM(price_pro * quantity_pro) as total_revenue'))
            ->groupBy('name_pro')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();
    
        return view('admin.chart.index', compact('orders', 'topProducts'));
    }
    
}
