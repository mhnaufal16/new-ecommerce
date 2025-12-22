<?php
// app/Http/Controllers/CartController.php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display the cart.
     */
    public function index()
    {
        $user = Auth::user();
        $cart = $user->cart()->firstOrCreate(['user_id' => $user->id]);
        $cart->load('items.product', 'items.variant');

        // Update prices for all items in cart to currently active prices
        foreach ($cart->items as $item) {
            $item->updatePrice();
        }

        return view('cart.index', compact('cart'));
    }

    /**
     * Add a product to the cart.
     */
    public function add(Request $request, Product $product)
    {
        $user = Auth::user();
        $cart = $user->cart()->firstOrCreate(['user_id' => $user->id]);
        
        $quantity = $request->input('quantity', 1);
        $variantId = $request->input('variant_id');
        
        // Add item using model method
        $cart->addItem($product->id, $quantity, $variantId);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang.',
                'cart_count' => $cart->total_quantity,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    /**
     * Update cart item quantity.
     */
    public function update(Request $request, CartItem $cartItem)
    {
        $this->authorizeOwner($cartItem);

        $quantity = $request->input('quantity');
        
        if ($quantity <= 0) {
            $cartItem->delete();
        } else {
            // Check stock before updating
            $inventory = $cartItem->variant 
                ? $cartItem->variant->inventory
                : $cartItem->product->inventory;
                
            if ($inventory && $inventory->available_quantity < $quantity) {
                return back()->with('error', 'Stok tidak mencukupi.');
            }
            
            $cartItem->update(['quantity' => $quantity]);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Keranjang diperbarui.',
            ]);
        }

        return back()->with('success', 'Keranjang diperbarui.');
    }

    /**
     * Remove an item from the cart.
     */
    public function remove(CartItem $cartItem)
    {
        $this->authorizeOwner($cartItem);
        $cartItem->delete();

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    /**
     * Clear the entire cart.
     */
    public function clear()
    {
        $user = Auth::user();
        if ($user->cart) {
            $user->cart->items()->delete();
        }

        return back()->with('success', 'Keranjang dikosongkan.');
    }

    /**
     * Apply a coupon code.
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|exists:coupons,code',
        ]);

        $user = Auth::user();
        $cart = $user->cart;

        if (!$cart) {
            return back()->with('error', 'Keranjang kosong.');
        }

        if ($cart->applyCoupon($request->coupon_code)) {
            return back()->with('success', 'Kupon berhasil diterapkan.');
        }

        return back()->with('error', 'Kupon tidak valid atau tidak dapat digunakan.');
    }

    /**
     * Remove the current coupon.
     */
    public function removeCoupon()
    {
        $user = Auth::user();
        if ($user->cart) {
            $user->cart->removeCoupon();
        }

        return back()->with('success', 'Kupon dihapus.');
    }

    /**
     * Helper to check if the user owns the cart item.
     */
    protected function authorizeOwner(CartItem $cartItem)
    {
        if ($cartItem->cart->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
