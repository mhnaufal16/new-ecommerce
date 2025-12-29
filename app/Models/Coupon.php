<?php
// app/Models/Coupon.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'discount_type',
        'discount_value',
        'min_order_amount',
        'max_discount_amount',
        'usage_limit',
        'usage_per_customer',
        'is_active',
        'starts_at',
        'expires_at',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    protected $attributes = [
        'discount_type' => 'percentage',
        'usage_per_customer' => 1,
        'is_active' => true,
    ];

    // RELATIONSHIPS
    public function products()
    {
        return $this->belongsToMany(Product::class, 'coupon_products');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'coupon_categories');
    }

    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    // SCOPES
    public function scopeActive($query)
    {
        $now = now();
        
        return $query->where('is_active', true)
                    ->where(function ($q) use ($now) {
                        $q->whereNull('starts_at')
                          ->orWhere('starts_at', '<=', $now);
                    })
                    ->where(function ($q) use ($now) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>=', $now);
                    });
    }

    public function scopeValid($query)
    {
        return $query->active()->where(function ($q) {
            $q->whereNull('usage_limit')
              ->orWhereRaw('usage_limit > (SELECT COUNT(*) FROM coupon_usages WHERE coupon_id = coupons.id)');
        });
    }

    // CUSTOM METHODS
    public function calculateDiscount($subtotal)
    {
        if ($this->min_order_amount && $subtotal < $this->min_order_amount) {
            return 0;
        }

        $discount = 0;

        switch ($this->discount_type) {
            case 'percentage':
                $discount = ($subtotal * $this->discount_value) / 100;
                break;
            case 'fixed_amount':
                $discount = $this->discount_value;
                break;
            case 'free_shipping':
                // Free shipping handled separately
                return 0;
        }

        // Apply max discount limit
        if ($this->max_discount_amount && $discount > $this->max_discount_amount) {
            $discount = $this->max_discount_amount;
        }

        return min($discount, $subtotal); // Discount can't exceed subtotal
    }

    public function isFreeShipping()
    {
        return $this->discount_type === 'free_shipping';
    }

    public function isValidForCart($cart)
    {
        // Check if coupon is active and valid
        if (!$this->isValid()) {
            return false;
        }

        // Check minimum order amount
        if ($this->min_order_amount && $cart->subtotal < $this->min_order_amount) {
            return false;
        }

        // Check if cart has required products/categories
        if ($this->products()->exists() || $this->categories()->exists()) {
            $cartProductIds = $cart->items->pluck('product_id');
            $cartCategoryIds = Product::whereIn('id', $cartProductIds)
                                     ->with('categories')
                                     ->get()
                                     ->flatMap(function ($product) {
                                         return $product->categories->pluck('id');
                                     })
                                     ->unique()
                                     ->values();

            // Check products
            if ($this->products()->exists()) {
                $requiredProductIds = $this->products()->pluck('products.id');
                if (!$cartProductIds->intersect($requiredProductIds)->count()) {
                    return false;
                }
            }

            // Check categories
            if ($this->categories()->exists()) {
                $requiredCategoryIds = $this->categories()->pluck('categories.id');
                if (!$cartCategoryIds->intersect($requiredCategoryIds)->count()) {
                    return false;
                }
            }
        }

        // Check per-customer usage limit
        if ($this->usage_per_customer && $cart->user_id) {
            $userUsageCount = $this->usages()->where('user_id', $cart->user_id)->count();
            if ($userUsageCount >= $this->usage_per_customer) {
                return false;
            }
        }

        return true;
    }

    public function isValid()
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();
        
        // Check date validity
        if ($this->starts_at && $now < $this->starts_at) {
            return false;
        }
        
        if ($this->expires_at && $now > $this->expires_at) {
            return false;
        }

        // Check usage limit
        if ($this->usage_limit && $this->usages()->count() >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    public function getUsageCountAttribute()
    {
        return $this->usages()->count();
    }

    public function getRemainingUsesAttribute()
    {
        if (!$this->usage_limit) {
            return null;
        }
        
        return max(0, $this->usage_limit - $this->usage_count);
    }

    public function getFormattedDiscountValueAttribute()
    {
        if ($this->discount_type === 'percentage') {
            return $this->discount_value . '%';
        } elseif ($this->discount_type === 'fixed_amount') {
            return 'Rp ' . number_format($this->discount_value, 0, ',', '.');
        } else {
            return 'Gratis Ongkir';
        }
    }

    public function getFormattedMinOrderAmountAttribute()
    {
        return $this->min_order_amount 
            ? 'Rp ' . number_format($this->min_order_amount, 0, ',', '.')
            : 'Tidak ada';
    }
}