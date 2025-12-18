<?php
// app/Models/Tax.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tax extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'rate',
        'type',
        'country_code',
        'province_id',
        'is_active',
        'priority',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected $attributes = [
        'type' => 'percentage',
        'country_code' => 'ID',
        'priority' => 0,
    ];

    // RELATIONSHIPS
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_taxes');
    }

    // SCOPES
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForCountry($query, $countryCode)
    {
        return $query->where('country_code', $countryCode);
    }

    public function scopeForProvince($query, $provinceId)
    {
        return $query->where('province_id', $provinceId)->orWhereNull('province_id');
    }

    // CUSTOM METHODS
    public function calculateTax($amount)
    {
        if ($this->type === 'percentage') {
            return ($amount * $this->rate) / 100;
        }
        
        return $this->rate; // Fixed amount
    }

    public function getFormattedRateAttribute()
    {
        if ($this->type === 'percentage') {
            return $this->rate . '%';
        }
        
        return 'Rp ' . number_format($this->rate, 0, ',', '.');
    }

    public function isPPN()
    {
        return $this->name === 'PPN' && $this->rate == 11;
    }
}