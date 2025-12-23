<?php
// app/Models/ShippingMethod.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'logo',
        'is_active',
        'is_cod_supported',
        'sort_order',
        'config',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_cod_supported' => 'boolean',
        'config' => 'array',
    ];

    // RELATIONSHIPS
    public function shippingRates()
    {
        return $this->hasMany(ShippingRate::class);
    }

    // SCOPES
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCodSupported($query)
    {
        return $query->where('is_cod_supported', true);
    }

    public function scopeSorted($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // CUSTOM METHODS
    public function getConfigValue($key, $default = null)
    {
        return data_get($this->config, $key, $default);
    }

    public function calculateCost($weight, $totalPrice, $destination)
    {
        $rate = $this->shippingRates()
            ->whereHas('shippingZone', function ($q) use ($destination) {
                $q->whereJsonContains('province_ids', (int) $destination)
                  ->orWhereNull('province_ids');
            })
            ->active()
            ->first();

        if (!$rate) {
            return null;
        }

        return $rate->calculateCost($weight, $totalPrice);
    }
}