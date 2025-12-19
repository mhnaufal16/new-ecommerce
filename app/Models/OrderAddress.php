<?php
// app/Models/OrderAddress.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderAddress extends Model
{
    protected $fillable = [
        'order_id',
        'address_type',
        'recipient_name',
        'phone',
        'province_id',
        'province_name',
        'city_id',
        'city_name',
        'district',
        'subdistrict',
        'postal_code',
        'address',
    ];

    // RELATIONSHIPS
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // CUSTOM METHODS
    public function getFullAddressAttribute()
    {
        $parts = [
            $this->address,
            $this->subdistrict,
            $this->district,
            $this->city_name,
            $this->province_name,
            $this->postal_code ? 'Kode Pos: ' . $this->postal_code : null,
        ];

        return implode(', ', array_filter($parts));
    }

    public function getAddressTypeLabelAttribute()
    {
        return $this->address_type === 'billing' ? 'Penagihan' : 'Pengiriman';
    }
}