<?php
// app/Models/CouponProduct.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponProduct extends Model
{
    protected $table = 'coupon_products';
    
    protected $fillable = [
        'coupon_id',
        'product_id',
    ];

    public $timestamps = false;

    // RELATIONSHIPS
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}