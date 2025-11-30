<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user_id = Auth::id();

        // Total Products
        $totalProducts = Product::where('user_id', $user_id)->count();

        // Total Stock
        $totalStock = Product::where('user_id', $user_id)->sum('stock');

        // Stock Utilization (asumsi kapasitas maksimal 1000 unit)
        $maxCapacity = 1000;
        $stockUtilization = $totalStock > 0 ? round(($totalStock / $maxCapacity) * 100) : 0;

        // Low Stock Products (stock < 50)
        $lowStockCount = Product::where('user_id', $user_id)
            ->where('stock', '<', 50)
            ->count();

        // Recent Products
        $recentProducts = Product::where('user_id', $user_id)
            ->latest()
            ->take(4)
            ->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalStock',
            'stockUtilization',
            'lowStockCount',
            'recentProducts'
        ));
    }
}
