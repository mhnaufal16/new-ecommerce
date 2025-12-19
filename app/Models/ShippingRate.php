<?php
// app/Models/ShippingRate.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingRate extends Model
{
    protected $fillable = [
        'shipping_method_id',
        'shipping_zone_id',
        'name',
        'description',
        'price_type',
        'min_weight',
        'max_weight',
        'min_price',
        'max_price',
        'flat_price',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'min_weight' => 'decimal:2',
        'max_weight' => 'decimal:2',
        'min_price' => 'decimal:2',
        'max_price' => 'decimal:2',
        'flat_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected $attributes = [
        'price_type' => 'flat',
    ];

    // RELATIONSHIPS
    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class);
    }

    public function shippingZone()
    {
        return $this->belongsTo(ShippingZone::class);
    }

    // SCOPES
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // CUSTOM METHODS
    public function calculateCost($weight, $totalPrice)
    {
        switch ($this->price_type) {
            case 'weight_based':
                if ($this->min_weight && $weight < $this->min_weight) {
                    return null;
                }
                if ($this->max_weight && $weight > $this->max_weight) {
                    return null;
                }
                return $this->flat_price;

            case 'price_based':
                if ($this->min_price && $totalPrice < $this->min_price) {
                    return null;
                }
                if ($this->max_price && $totalPrice > $this->max_price) {
                    return null;
                }
                return $this->flat_price;

            case 'flat':
            default:
                return $this->flat_price;
        }
    }

    public function isApplicable($weight, $totalPrice)
    {
        switch ($this->price_type) {
            case 'weight_based':
                if ($this->min_weight && $weight < $this->min_weight) {
                    return false;
                }
                if ($this->max_weight && $weight > $this->max_weight) {
                    return false;
                }
                break;

            case 'price_based':
                if ($this->min_price && $totalPrice < $this->min_price) {
                    return false;
                }
                if ($this->max_price && $totalPrice > $this->max_price) {
                    return false;
                }
                break;
        }

        return true;
    }

    public function getFormattedFlatPriceAttribute()
    {
        return $this->flat_price ? 'Rp ' . number_format($this->flat_price, 0, ',', '.') : null;
    }
}