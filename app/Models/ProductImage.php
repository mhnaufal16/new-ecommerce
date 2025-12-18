<?php
// app/Models/ProductImage.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image_url',
        'thumbnail_url',
        'alt_text',
        'sort_order',
        'is_main',
    ];

    protected $casts = [
        'is_main' => 'boolean',
    ];

    // RELATIONSHIPS
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // SCOPES
    public function scopeMain($query)
    {
        return $query->where('is_main', true);
    }

    // CUSTOM METHODS
    public function setAsMain()
    {
        // Set semua gambar produk ini menjadi non-main
        $this->product->images()->update(['is_main' => false]);
        
        // Set ini sebagai main
        $this->update(['is_main' => true]);
        
        return $this;
    }
}