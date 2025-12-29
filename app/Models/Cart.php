<?php
// app/Models/Cart.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'coupon_code',
        'currency',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $attributes = [
        'currency' => 'IDR',
    ];

    // RELATIONSHIPS
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_code', 'code');
    }

    // SCOPES
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForSession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    public function scopeActive($query)
    {
        return $query->whereHas('items');
    }

    // CUSTOM METHODS
    public function addItem($productId, $quantity = 1, $variantId = null, $customizations = null)
    {
        // Cari apakah item sudah ada di cart
        $existingItem = $this->items()
            ->where('product_id', $productId)
            ->where('variant_id', $variantId)
            ->first();

        if ($existingItem) {
            // Update quantity jika sudah ada
            $existingItem->increment('quantity', $quantity);
            return $existingItem;
        }

        // Dapatkan harga produk
        $product = Product::findOrFail($productId);
        $price = $variantId 
            ? ProductVariant::findOrFail($variantId)->current_price
            : $product->current_price;

        // Buat item baru
        return $this->items()->create([
            'product_id' => $productId,
            'variant_id' => $variantId,
            'quantity' => $quantity,
            'price' => $price,
            'customizations' => $customizations,
        ]);
    }

    public function removeItem($itemId)
    {
        return $this->items()->where('id', $itemId)->delete();
    }

    public function updateItemQuantity($itemId, $quantity)
    {
        if ($quantity <= 0) {
            return $this->removeItem($itemId);
        }

        return $this->items()->where('id', $itemId)->update(['quantity' => $quantity]);
    }

    public function clear()
    {
        return $this->items()->delete();
    }

    public function getSubtotalAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    }

    public function getTotalQuantityAttribute()
    {
        return $this->items->sum('quantity');
    }

    public function getItemCountAttribute()
    {
        return $this->items->count();
    }

    public function getDiscountAmountAttribute()
    {
        if (!$this->coupon) {
            return 0;
        }

        return $this->coupon->calculateDiscount($this->subtotal);
    }

    public function getTaxAmountAttribute()
    {
        // Hitung tax berdasarkan produk di cart
        $taxTotal = 0;
        
        foreach ($this->items as $item) {
            $product = $item->product;
            foreach ($product->taxes as $tax) {
                $taxTotal += $tax->calculateTax($item->price * $item->quantity);
            }
        }
        
        return $taxTotal;
    }

    public function getGrandTotalAttribute()
    {
        return $this->subtotal + $this->tax_amount - $this->discount_amount;
    }

    public function applyCoupon($couponCode)
    {
        $coupon = Coupon::where('code', $couponCode)
                       ->active()
                       ->first();

        if (!$coupon || !$coupon->isValidForCart($this)) {
            return false;
        }

        if (!$coupon) {
            return false;
        }

        $this->update(['coupon_code' => $couponCode]);
        return true;
    }

    public function removeCoupon()
    {
        $this->update(['coupon_code' => null]);
        return true;
    }

    public function mergeWithSessionCart($sessionCart)
    {
        if (!$sessionCart) {
            return $this;
        }

        foreach ($sessionCart->items as $item) {
            $this->addItem(
                $item->product_id,
                $item->quantity,
                $item->variant_id,
                $item->customizations
            );
        }

        // Hapus cart session setelah merge
        $sessionCart->delete();

        return $this;
    }

    public function validateStock()
    {
        $errors = [];
        
        foreach ($this->items as $item) {
            $inventory = $item->variant 
                ? $item->variant->inventory
                : $item->product->inventory;
            
            if (!$inventory || $inventory->available_quantity < $item->quantity) {
                $errors[] = "Produk {$item->product->name} stok tidak cukup. Stok tersedia: " . ($inventory->available_quantity ?? 0);
            }
        }
        
        return $errors;
    }
}