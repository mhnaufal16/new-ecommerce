<?php
// app/Models/CartItem.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartItem extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    protected $fillable = [
        'cart_id',
        'product_id',
        'variant_id',
        'quantity',
        'price',
        'customizations',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'customizations' => 'array',
    ];

    // RELATIONSHIPS
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    // CUSTOM METHODS
    public function getTotalAttribute()
    {
        return $this->price * $this->quantity;
    }

    public function getProductNameAttribute()
    {
        $name = $this->product->name;
        
        if ($this->variant) {
            $name .= ' - ' . $this->variant->attributes_text;
        }
        
        return $name;
    }

    public function getThumbnailAttribute()
    {
        return $this->product->thumbnail_url;
    }

    public function getStockStatusAttribute()
    {
        $inventory = $this->variant 
            ? $this->variant->inventory
            : $this->product->inventory;
        
        if (!$inventory) {
            return 'out_of_stock';
        }
        
        return $inventory->available_quantity >= $this->quantity 
            ? 'in_stock' 
            : 'low_stock';
    }

    public function getAvailableStockAttribute()
    {
        $inventory = $this->variant 
            ? $this->variant->inventory
            : $this->product->inventory;
        
        return $inventory ? $inventory->available_quantity : 0;
    }

    public function incrementQuantity($amount = 1)
    {
        $this->increment('quantity', $amount);
        return $this->fresh();
    }

    public function decrementQuantity($amount = 1)
    {
        if ($this->quantity <= $amount) {
            $this->delete();
            return null;
        }
        
        $this->decrement('quantity', $amount);
        return $this->fresh();
    }

    public function updatePrice()
    {
        $newPrice = $this->variant 
            ? $this->variant->current_price
            : $this->product->current_price;
        
        if ($newPrice != $this->price) {
            $this->update(['price' => $newPrice]);
        }
        
        return $this;
    }
}