<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $vendorId = auth()->id();
        
        // Orders containing products from this vendor
        $orders = Order::whereHas('items', function($query) use ($vendorId) {
            $query->whereHas('product', function($q) use ($vendorId) {
                $q->where('vendor_id', $vendorId);
            });
        })
        ->with(['user'])
        ->latest()
        ->paginate(10);
            
        return view('vendor.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $vendorId = auth()->id();
        
        // Verify this order belongs to this vendor
        $hasVendorProduct = $order->items()->whereHas('product', function($q) use ($vendorId) {
            $q->where('vendor_id', $vendorId);
        })->exists();

        if (!$hasVendorProduct) {
            abort(403, 'Akses ditolak.');
        }

        // Load items specific to this vendor
        $vendorItems = $order->items()->whereHas('product', function($q) use ($vendorId) {
            $q->where('vendor_id', $vendorId);
        })->with('product')->get();

        return view('vendor.orders.show', compact('order', 'vendorItems'));
    }
}
