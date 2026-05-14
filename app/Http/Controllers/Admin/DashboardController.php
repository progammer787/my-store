<?php
// ============================================================
// app/Http/Controllers/Admin/DashboardController.php
// ============================================================
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Order, Product, Category};

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_orders'    => Order::count(),
            'pending_orders'  => Order::where('status', 'pending')->count(),
            'total_products'  => Product::count(),
            'total_revenue'   => Order::whereIn('status', ['confirmed', 'delivered'])->sum('total'),
            'total_categories'=> Category::count(),
            'low_stock'       => Product::where('stock', '<=', 5)->where('stock', '>', 0)->count(),
            'out_of_stock'    => Product::where('stock', 0)->count(),
        ];

        $recentOrders = Order::with('items')->latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }
}
