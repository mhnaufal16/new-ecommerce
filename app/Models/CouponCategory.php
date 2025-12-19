<?php
// app/Models/CouponCategory.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponCategory extends Model
{
    protected $table = 'coupon_categories';
    
    protected $fillable = [
        'coupon_id',
        'category_id',
    ];

    public $timestamps = false;

    // RELATIONSHIPS
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}