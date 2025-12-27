<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index()
    {
        $period = request('period', 'all');
        $query = Order::paid();

        switch ($period) {
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'week':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year);
                break;
            case 'year':
                $query->whereYear('created_at', Carbon::now()->year);
                break;
        }

        // Summary Totals (Filtered by period)
        $totalSales = (clone $query)->sum('grand_total');
        $totalOrders = (clone $query)->count();
        $averageOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;
        
        // Total stats (Always all time for context)
        $totalCustomers = User::where('type', 'customer')->count();

        // Monthly Stats (Last 12 months)
        $monthlySales = Order::paid()
            ->select(
                DB::raw('SUM(grand_total) as total'),
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month")
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(11))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Top Selling Products (Filtered)
        $topProducts = Product::select('products.*', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'paid');
        
        if ($period !== 'all') {
            $topProducts->where('orders.created_at', '>=', $this->getStartDate($period));
        }
            
        $topProducts = $topProducts->groupBy('products.id')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->with(['activePrice', 'images', 'mainImage'])
            ->get();

        // Top Customers (All time)
        $topCustomers = User::select('users.id', 'users.name', 'users.email', DB::raw('SUM(orders.grand_total) as total_spent'), DB::raw('COUNT(orders.id) as order_count'))
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->where('orders.payment_status', 'paid')
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('total_spent')
            ->limit(5)
            ->get();

        // Sales by Category (More robust query - uses primary if exists, otherwise any)
        $salesByCategory = Category::select('categories.name', DB::raw('SUM(order_items.row_total) as revenue'))
            ->join('product_categories', 'categories.id', '=', 'product_categories.category_id')
            ->join('order_items', 'product_categories.product_id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'paid')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('revenue')
            ->get();

        // Order Status Distribution
        $orderStatuses = Order::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        return view('admin.analytics.index', compact(
            'totalSales', 'totalOrders', 'totalCustomers', 'averageOrderValue',
            'monthlySales', 'topProducts', 'topCustomers', 'salesByCategory', 'orderStatuses', 'period'
        ));
    }

    private function getStartDate($period)
    {
        switch ($period) {
            case 'today': return Carbon::today();
            case 'week': return Carbon::now()->startOfWeek();
            case 'month': return Carbon::now()->startOfMonth();
            case 'year': return Carbon::now()->startOfYear();
            default: return Carbon::now()->subYears(10);
        }
    }
}
