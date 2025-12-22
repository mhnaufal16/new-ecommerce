<?php
// app/Http/Controllers/CheckoutController.php
namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderAddress;
use App\Models\UserAddress;
use App\Models\ShippingMethod;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Show the checkout page.
     */
    public function index()
    {
        $user = Auth::user();
        $cart = $user->cart;

        if (!$cart || $cart->items->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda kosong.');
        }

        // Validate stock before checkout
        $stockErrors = $cart->validateStock();
        if (!empty($stockErrors)) {
            return redirect()->route('cart.index')->withErrors($stockErrors);
        }

        $addresses = $user->addresses()->latest()->get();
        $shippingMethods = ShippingMethod::active()->sorted()->get();
        $paymentMethods = PaymentMethod::active()->sorted()->get();

        return view('checkout.index', compact('cart', 'addresses', 'shippingMethods', 'paymentMethods'));
    }

    /**
     * Process the checkout and create an order.
     */
    public function process(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:user_addresses,id',
            'shipping_method_id' => 'required|exists:shipping_methods,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'notes' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $cart = $user->cart;

        if (!$cart || $cart->items->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda kosong.');
        }

        $address = UserAddress::findOrFail($request->address_id);
        $shippingMethod = ShippingMethod::findOrFail($request->shipping_method_id);
        $paymentMethod = PaymentMethod::findOrFail($request->payment_method_id);

        // Calculate shipping cost (simplified logic for now)
        $weight = $cart->items->sum(function($item) {
            return ($item->variant ? $item->variant->weight : $item->product->weight) * $item->quantity;
        });
        
        // Use model method to calculate cost if available, otherwise use a default or fixed
        $shippingCost = $shippingMethod->calculateCost($weight, $cart->subtotal, $address->province_id) ?? 15000;

        return DB::transaction(function () use ($user, $cart, $address, $shippingMethod, $paymentMethod, $shippingCost, $request) {
            // 1. Create Order
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => $user->id,
                'customer_email' => $user->email,
                'customer_phone' => $address->phone,
                'status' => 'pending',
                'payment_status' => 'pending',
                'shipping_status' => 'pending',
                'subtotal' => $cart->subtotal,
                'discount_amount' => $cart->discount_amount,
                'tax_amount' => $cart->tax_amount,
                'shipping_amount' => $shippingCost,
                'grand_total' => $cart->grand_total + $shippingCost,
                'notes' => $request->notes,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // 2. Create Order Items
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id,
                    'product_name' => $item->product_name,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->price,
                    'row_total' => $item->total,
                ]);

                // 3. Update stock (decrement)
                $inventory = $item->variant ? $item->variant->inventory : $item->product->inventory;
                if ($inventory) {
                    $inventory->decrement('quantity', $item->quantity);
                }
            }

            // 4. Create Order Address
            OrderAddress::create([
                'order_id' => $order->id,
                'address_type' => 'shipping',
                'recipient_name' => $address->recipient_name,
                'phone' => $address->phone,
                'province_id' => $address->province_id,
                'province_name' => $address->province_name,
                'city_id' => $address->city_id,
                'city_name' => $address->city_name,
                'district' => $address->district,
                'subdistrict' => $address->subdistrict,
                'postal_code' => $address->postal_code,
                'address' => $address->address,
            ]);

            // 5. Handle Coupon (if any)
            if ($cart->coupon_code) {
                // Record coupon usage logic here if needed
            }

            // 6. Clear Cart
            $cart->items()->delete();
            $cart->update(['coupon_code' => null]);

            return redirect()->route('orders.show', $order)->with('success', 'Pesanan Anda berhasil dibuat! Silakan lakukan pembayaran.');
        });
    }
}
