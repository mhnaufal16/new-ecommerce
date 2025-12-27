<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $vendorId = auth()->id();

        // Vendor Metrics
        $totalProducts = Product::where('vendor_id', $vendorId)->count();
        $activeProducts = Product::where('vendor_id', $vendorId)->where('status', 'active')->count();
        
        // Orders metrics (orders that contain at least one product from this vendor)
        $vendorOrders = Order::whereHas('items', function($query) use ($vendorId) {
            $query->whereHas('product', function($q) use ($vendorId) {
                $q->where('vendor_id', $vendorId);
            });
        })->get();

        $totalOrders = $vendorOrders->count();
        
        // Revenue (only for items belonging to this vendor)
        $totalRevenue = OrderItem::whereHas('product', function($q) use ($vendorId) {
            $q->where('vendor_id', $vendorId);
        })->whereHas('order', function($q) {
            $q->where('payment_status', 'paid');
        })->sum('row_total');

        // Recent Orders for this vendor
        $recentOrders = Order::whereHas('items', function($query) use ($vendorId) {
            $query->whereHas('product', function($q) use ($vendorId) {
                $q->where('vendor_id', $vendorId);
            });
        })->latest()->limit(5)->get();

        return view('vendor.dashboard.index', compact(
            'totalProducts',
            'activeProducts',
            'totalOrders',
            'totalRevenue',
            'recentOrders'
        ));
    }
}
