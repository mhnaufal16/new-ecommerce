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
    public function getUrlAttribute()
    {
        $url = $this->image_url;
        if (!$url) return "https://picsum.photos/seed/" . md5($this->id . 'url') . "/800/800";

        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return $url;
        }

        $path = ltrim($url, '/\\');
        if (str_starts_with($path, 'storage/')) $path = substr($path, 8);
        
        if (file_exists(public_path('storage/' . $path))) {
            return asset('storage/' . $path);
        }

        return "https://picsum.photos/seed/" . md5($this->id . 'url') . "/800/800";
    }

    public function getThumbnailAttribute()
    {
        $url = $this->thumbnail_url ?: $this->image_url;
        if (!$url) return "https://picsum.photos/seed/" . md5($this->id . 'thumb') . "/400/400";

        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return $url;
        }

        $path = ltrim($url, '/\\');
        if (str_starts_with($path, 'storage/')) $path = substr($path, 8);

        if (file_exists(public_path('storage/' . $path))) {
            return asset('storage/' . $path);
        }

        return "https://picsum.photos/seed/" . md5($this->id . 'thumb') . "/400/400";
    }

    public function setAsMain()
    {
        // Set semua gambar produk ini menjadi non-main
        $this->product->images()->update(['is_main' => false]);
        
        // Set ini sebagai main
        $this->update(['is_main' => true]);
        
        return $this;
    }
}