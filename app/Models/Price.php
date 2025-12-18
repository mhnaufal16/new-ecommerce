<?php
// app/Models/Price.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Price extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'variant_id',
        'base_price',
        'sale_price',
        'sale_start_date',
        'sale_end_date',
        'cost',
        'currency',
        'is_active',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'cost' => 'decimal:2',
        'is_active' => 'boolean',
        'sale_start_date' => 'date',
        'sale_end_date' => 'date',
    ];

    protected $attributes = [
        'currency' => 'IDR',
        'is_active' => true,
    ];

    // RELATIONSHIPS
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    // SCOPES
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOnSale($query)
    {
        $now = now()->toDateString();
        
        return $query->whereNotNull('sale_price')
                    ->where(function ($q) use ($now) {
                        $q->whereNull('sale_start_date')
                          ->orWhere('sale_start_date', '<=', $now);
                    })
                    ->where(function ($q) use ($now) {
                        $q->whereNull('sale_end_date')
                          ->orWhere('sale_end_date', '>=', $now);
                    });
    }

    // CUSTOM METHODS
    public function isSaleActive()
    {
        if (!$this->sale_price) {
            return false;
        }

        $now = now();
        $start = $this->sale_start_date ?: $now->subDay();
        $end = $this->sale_end_date ?: $now->addDay();

        return $now->between($start, $end);
    }

    public function getCurrentPriceAttribute()
    {
        if ($this->isSaleActive()) {
            return $this->sale_price;
        }
        
        return $this->base_price;
    }

    public function getDiscountPercentageAttribute()
    {
        if (!$this->isSaleActive() || $this->base_price <= 0) {
            return 0;
        }

        $discount = $this->base_price - $this->sale_price;
        return round(($discount / $this->base_price) * 100, 2);
    }

    public function getDiscountAmountAttribute()
    {
        if (!$this->isSaleActive()) {
            return 0;
        }

        return $this->base_price - $this->sale_price;
    }

    public function getProfitMarginAttribute()
    {
        if (!$this->cost || $this->cost <= 0) {
            return null;
        }

        $currentPrice = $this->current_price;
        $profit = $currentPrice - $this->cost;
        
        return round(($profit / $this->cost) * 100, 2);
    }

    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->current_price, 0, ',', '.');
    }

    public function getFormattedBasePriceAttribute()
    {
        return 'Rp ' . number_format($this->base_price, 0, ',', '.');
    }

    public function getFormattedSalePriceAttribute()
    {
        if (!$this->sale_price) {
            return null;
        }
        
        return 'Rp ' . number_format($this->sale_price, 0, ',', '.');
    }
}