<?php
// app/Models/ProductVariant.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'special_price',
        'special_price_from',
        'special_price_to',
        'cost',
        'weight',
        'length',
        'width',
        'height',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'special_price' => 'decimal:2',
        'cost' => 'decimal:2',
        'weight' => 'decimal:2',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'special_price_from' => 'date',
        'special_price_to' => 'date',
    ];

    // RELATIONSHIPS
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function attributeValues()
    {
        return $this->belongsToMany(
            ProductAttributeValue::class,
            'product_variant_attributes',
            'variant_id',
            'attribute_value_id'
        );
    }

    public function attributes()
    {
        return $this->hasMany(ProductVariantAttribute::class);
    }

    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

    public function prices()
    {
        return $this->hasMany(Price::class);
    }

    public function activePrice()
    {
        return $this->hasOne(Price::class)->where('is_active', true);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // CUSTOM METHODS
    public function getCurrentPriceAttribute()
    {
        $price = $this->activePrice;
        
        if (!$price) {
            return $this->getSalePrice() ?? $this->price;
        }

        if ($price->sale_price && $price->isSaleActive()) {
            return $price->sale_price;
        }

        return $price->base_price;
    }

    public function getSalePrice()
    {
        if (!$this->special_price) {
            return null;
        }

        $now = now();
        $from = $this->special_price_from ?: $now->subDay();
        $to = $this->special_price_to ?: $now->addDay();

        if ($now->between($from, $to)) {
            return $this->special_price;
        }

        return null;
    }

    public function isOnSale()
    {
        return $this->getSalePrice() !== null;
    }

    public function getStockQuantityAttribute()
    {
        return $this->inventory ? $this->inventory->quantity : 0;
    }

    public function getStockStatusAttribute()
    {
        return $this->inventory ? $this->inventory->stock_status : 'out_of_stock';
    }

    public function isInStock()
    {
        return $this->stock_status === 'in_stock' && $this->stock_quantity > 0;
    }

    public function getAttributesTextAttribute()
    {
        return $this->attributeValues->map(function ($value) {
            return "{$value->attribute->name}: {$value->value}";
        })->implode(', ');
    }

    public function getDimensionsAttribute()
    {
        if ($this->length && $this->width && $this->height) {
            return "{$this->length} × {$this->width} × {$this->height} cm";
        }
        return null;
    }

    public function getVolumeWeightAttribute()
    {
        if ($this->length && $this->width && $this->height) {
            return ($this->length * $this->width * $this->height) / 6000; // Formula volume weight
        }
        return $this->weight;
    }
}