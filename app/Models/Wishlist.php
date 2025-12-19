<?php
// app/Models/Wishlist.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
    ];

    // RELATIONSHIPS
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // SCOPES
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // CUSTOM METHODS
    public function getProductDetailsAttribute()
    {
        return [
            'id' => $this->product->id,
            'name' => $this->product->name,
            'slug' => $this->product->slug,
            'price' => $this->product->current_price,
            'image' => $this->product->thumbnail_url,
            'in_stock' => $this->product->isInStock(),
        ];
    }
}