<?php
// app/Models/UserAddress.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'label',
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
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    // RELATIONSHIPS
    public function user()
    {
        return $this->belongsTo(User::class);
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

    public function makePrimary()
    {
        // Set semua address user ini menjadi non-primary
        $this->user->addresses()->update(['is_primary' => false]);
        
        // Set ini sebagai primary
        $this->update(['is_primary' => true]);
        
        return $this;
    }
}