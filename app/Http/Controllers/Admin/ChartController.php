<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    public function index(){
        $bills = Bill::selectRaw('DATE(date) as date, SUM(total) as total')
        ->groupBy('date')
        ->orderBy('date', 'asc')
        ->get();
        $labels = $bills->pluck('date');
        $data = $bills->pluck('total');

    return view('admin.chart.index', compact('labels', 'data'));
    }
}
