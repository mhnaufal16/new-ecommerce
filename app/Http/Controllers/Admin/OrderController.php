<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user')->latest();

        // Status Filter
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->paginate(15);
        
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'billingAddress', 'shippingAddress']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,on_hold,completed,cancelled,refunded,failed',
        ]);

        $order->updateStatus($validated['status']);

        return back()->with('success', 'Order status updated successfully.');
    }
    
    public function updatePaymentStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:pending,paid,partially_paid,refunded,failed',
        ]);

        $order->updatePaymentStatus($validated['payment_status']);

        return back()->with('success', 'Payment status updated successfully.');
    }

    public function updateShippingStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'shipping_status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order->updateShippingStatus($validated['shipping_status']);

        return back()->with('success', 'Shipping status updated successfully.');
    }
}
