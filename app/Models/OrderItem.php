<?php
// app/Models/OrderItem.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'variant_id',
        'sku',
        'product_name',
        'variant_attributes',
        'quantity',
        'unit_price',
        'discount_amount',
        'tax_amount',
        'row_total',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'row_total' => 'decimal:2',
        'variant_attributes' => 'array',
    ];

    // RELATIONSHIPS
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    // CUSTOM METHODS
    public function getFormattedUnitPriceAttribute()
    {
        return 'Rp ' . number_format($this->unit_price, 0, ',', '.');
    }

    public function getFormattedRowTotalAttribute()
    {
        return 'Rp ' . number_format($this->row_total, 0, ',', '.');
    }

    public function getProductWithAttributesAttribute()
    {
        $name = $this->product_name;
        
        if ($this->variant_attributes && count($this->variant_attributes) > 0) {
            $attributes = collect($this->variant_attributes)->map(function ($attr) {
                return $attr['attribute'] . ': ' . $attr['value'];
            })->implode(', ');
            
            $name .= ' (' . $attributes . ')';
        }
        
        return $name;
    }

    public function canBeReviewed()
    {
        return !$this->review && 
               $this->order->status === 'completed' &&
               $this->order->shipping_status === 'delivered';
    }
}