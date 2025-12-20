<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index()
    {
        // Totals
        $totalSales = Order::paid()->sum('grand_total');
        $totalOrders = Order::count();
        $totalCustomers = User::where('type', 'customer')->count();
        $averageOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;

        // Monthly Stats (Last 6 months)
        $monthlySales = Order::paid()
            ->select(
                DB::raw('SUM(grand_total) as total'),
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month")
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Top Selling Products
        $topProducts = Product::select('products.id', 'products.name', 'products.slug', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'paid')
            ->groupBy('products.id', 'products.name', 'products.slug')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->with(['activePrice', 'mainImage'])
            ->get();

        // Recent Orders
        $recentOrders = Order::with('user')->latest()->limit(5)->get();

        return view('admin.analytics.index', compact(
            'totalSales', 'totalOrders', 'totalCustomers', 'averageOrderValue',
            'monthlySales', 'topProducts', 'recentOrders'
        ));
    }
}
