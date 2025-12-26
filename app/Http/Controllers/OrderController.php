<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $orders = $user->orders()->latest()->paginate(10);
        
        return view('orders.index', compact('orders'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Auth::user()->orders()->with('items.product', 'addresses', 'payments')->findOrFail($id);
        
        return view('orders.show', compact('order'));
    }

    /**
     * Show the payment page for the order.
     */
    public function pay(string $id)
    {
        $order = Auth::user()->orders()->with('payments')->findOrFail($id);

        if ($order->payment_status === 'paid') {
            return redirect()->route('orders.show', $order)->with('info', 'Pesanan ini sudah lunas.');
        }

        $payment = $order->payments()->where('transaction_status', 'pending')->latest()->first();
        
        // If no payment record exists, we might need to create one or 
        // fallback to a default if we can't determine the method.
        // For now, let's assume we can find it.
        
        if (!$payment) {
            // Fallback for older orders that didn't have a payment record created
            // We create a default bank transfer payment so the user can see the page
            $payment = $order->payments()->create([
                'payment_method' => 'bank_transfer',
                'amount' => $order->grand_total,
                'currency' => $order->currency,
                'transaction_status' => 'pending',
                'payment_details' => ['note' => 'Auto-created fallback']
            ]);
        }

        $paymentMethod = \App\Models\PaymentMethod::where('code', $payment->payment_method)->first();

        return view('orders.pay', compact('order', 'payment', 'paymentMethod'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
