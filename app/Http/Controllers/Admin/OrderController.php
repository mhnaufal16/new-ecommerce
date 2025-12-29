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
            'courier_name' => 'nullable|string|max:50',
            'tracking_number' => 'nullable|string|max:100',
        ]);

        $order->updateShippingStatus($validated['shipping_status']);

        // Handle shipping details if status is shipped or processing/delivered
        if ($request->filled('courier_name') || $request->filled('tracking_number')) {
            $shipment = $order->shipments()->first();
            
            if ($shipment) {
                $shipment->update([
                    'courier_name' => $validated['courier_name'] ?? $shipment->courier_name,
                    'tracking_number' => $validated['tracking_number'] ?? $shipment->tracking_number,
                ]);
            } else {
                $order->shipments()->create([
                    'shipping_method' => 'standard',
                    'courier_name' => $validated['courier_name'] ?? 'Other',
                    'courier_service' => 'Standard',
                    'shipping_cost' => $order->shipping_amount,
                    'tracking_number' => $validated['tracking_number'],
                ]);
            }

            if ($validated['shipping_status'] === 'shipped') {
                $order->markAsShipped($validated['tracking_number']);
            }
        }

        if ($validated['shipping_status'] === 'delivered') {
            $order->markAsDelivered();
        }

        return back()->with('success', 'Shipping status updated successfully.');
    }
    public function approvePayment(Order $order)
    {
        $payment = $order->payments()->where('verification_status', 'pending')->latest()->first();

        if (!$payment) {
            return back()->with('error', 'Bukti pembayaran tidak ditemukan.');
        }

        \DB::transaction(function () use ($order, $payment) {
            $payment->update([
                'verification_status' => 'verified',
                'transaction_status' => 'settlement',
                'paid_at' => now(),
            ]);

            $order->update([
                'payment_status' => 'paid',
                'status' => 'processing',
                'total_paid' => $order->grand_total,
            ]);
        });

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi.');
    }

    public function rejectPayment(Request $request, Order $order)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $payment = $order->payments()->where('verification_status', 'pending')->latest()->first();

        if (!$payment) {
            return back()->with('error', 'Bukti pembayaran tidak ditemukan.');
        }

        $payment->update([
            'verification_status' => 'rejected',
            'rejection_reason' => $request->reason,
        ]);

        $order->update([
            'payment_status' => 'pending', // Revert to pending
        ]);

        return back()->with('warning', 'Pembayaran ditolak.');
    }
}
