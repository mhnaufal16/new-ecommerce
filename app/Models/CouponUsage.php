<?php
// app/Models/CouponUsage.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponUsage extends Model
{
    protected $table = 'coupon_usages';

    protected $fillable = [
        'coupon_id',
        'order_id',
        'user_id',
        'discount_amount',
        'used_at',
    ];

    protected $casts = [
        'discount_amount' => 'decimal:2',
        'used_at' => 'datetime',
    ];

    // RELATIONSHIPS
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // SCOPES
    public function scopeToday($query)
    {
        return $query->whereDate('used_at', today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('used_at', now()->month)
                    ->whereYear('used_at', now()->year);
    }

    // CUSTOM METHODS
    public function getFormattedDiscountAmountAttribute()
    {
        return 'Rp ' . number_format($this->discount_amount, 0, ',', '.');
    }
}