<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'products' => Product::count(),
            'active_products' => Product::where('is_active', true)->count(),
            'categories' => Category::count(),
            'orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'revenue' => Order::whereIn('status', ['paid','processing','shipped','completed'])->sum('total'),
        ];
        $latestOrders = Order::latest()->take(8)->get();
        $lowStock = Product::where('stock','<=',5)->orderBy('stock')->take(8)->get();
        return view('admin.dashboard', compact('stats','latestOrders','lowStock'));
    }
}
