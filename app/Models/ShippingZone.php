<?php
// app/Models/ShippingZone.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingZone extends Model
{
    protected $fillable = [
        'name',
        'country_code',
        'province_ids',
        'is_active',
    ];

    protected $casts = [
        'province_ids' => 'array',
        'is_active' => 'boolean',
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

    public function scopeForProvince($query, $provinceId)
    {
        return $query->where(function ($q) use ($provinceId) {
            $q->whereJsonContains('province_ids', (int) $provinceId)
              ->orWhereNull('province_ids');
        });
    }

    // CUSTOM METHODS
    public function getProvincesAttribute()
    {
        if (!$this->province_ids) {
            return collect(); // Semua provinsi
        }

        // Ini akan return array province IDs
        // Untuk nama provinsi, perlu integrasi dengan data provinsi eksternal
        return collect($this->province_ids);
    }

    public function isGlobal()
    {
        return empty($this->province_ids);
    }

    public function includesProvince($provinceId)
    {
        if ($this->isGlobal()) {
            return true;
        }

        return in_array((int) $provinceId, $this->province_ids ?? []);
    }
}