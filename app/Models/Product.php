<?php
// app/Models/Product.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'name',
        'slug',
        'brand_id',
        'short_description',
        'description',
        'specifications',
        'type',
        'status',
        'is_featured',
        'is_new',
        'is_virtual',
        'tax_class_id',
        'minimum_order_qty',
        'maximum_order_qty',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'specifications' => 'array',
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
        'is_virtual' => 'boolean',
        'minimum_order_qty' => 'integer',
        'maximum_order_qty' => 'integer',
    ];

    protected $attributes = [
        'type' => 'simple',
        'status' => 'draft',
    ];

    // RELATIONSHIPS
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories')
                    ->withPivot('is_primary');
    }

    public function primaryCategory()
    {
        return $this->belongsToMany(Category::class, 'product_categories')
                    ->wherePivot('is_primary', true)
                    ->take(1);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function mainImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_main', true);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function attributes()
    {
        return $this->hasManyThrough(
            ProductAttribute::class,
            ProductVariantAttribute::class,
            'variant_id',
            'id',
            'id',
            'attribute_id'
        )->distinct();
    }

    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

    public function prices()
    {
        return $this->hasMany(Price::class);
    }

    public function activePrice()
    {
        return $this->hasOne(Price::class)->where('is_active', true);
    }

    public function taxes()
    {
        return $this->belongsToMany(Tax::class, 'product_taxes');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // SCOPES
    public function scopeActive(Builder $query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured(Builder $query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeNew(Builder $query)
    {
        return $query->where('is_new', true);
    }

    public function scopeSimple(Builder $query)
    {
        return $query->where('type', 'simple');
    }

    public function scopeConfigurable(Builder $query)
    {
        return $query->where('type', 'configurable');
    }

    public function scopeDigital(Builder $query)
    {
        return $query->where('is_virtual', true);
    }

    public function scopeInStock(Builder $query)
    {
        return $query->whereHas('inventory', function ($q) {
            $q->where('stock_status', 'in_stock')
              ->where('quantity', '>', 0);
        });
    }

    public function scopeByCategory(Builder $query, $categoryId)
    {
        return $query->whereHas('categories', function ($q) use ($categoryId) {
            $q->where('categories.id', $categoryId);
        });
    }

    public function scopeByBrand(Builder $query, $brandId)
    {
        return $query->where('brand_id', $brandId);
    }

    public function getBreadcrumbAttribute()
    {
        $primaryCategory = $this->categories()->wherePivot('is_primary', true)->first() 
            ?: $this->categories()->first();
            
        if (!$primaryCategory) {
            return collect();
        }
        
        return collect($primaryCategory->breadcrumb);
    }

    // CUSTOM METHODS
    public function getAverageRatingAttribute()
    {
        return $this->approvedReviews()->avg('rating');
    }

    public function getReviewCountAttribute()
    {
        return $this->approvedReviews()->count();
    }

    public function getWishlistCountAttribute()
    {
        return $this->wishlists()->count();
    }

    public function getCurrentPriceAttribute()
    {
        $price = $this->activePrice;
        
        if (!$price) {
            return null;
        }

        // Cek jika ada sale price yang aktif
        if ($price->sale_price && $price->isSaleActive()) {
            return $price->sale_price;
        }

        return $price->base_price;
    }

    public function getStockQuantityAttribute()
    {
        return $this->inventory ? $this->inventory->quantity : 0;
    }

    public function getStockStatusAttribute()
    {
        return $this->inventory ? $this->inventory->stock_status : 'out_of_stock';
    }

    public function isInStock()
    {
        return $this->stock_status === 'in_stock' && $this->stock_quantity > 0;
    }

    public function getMainImageUrlAttribute()
    {
        $mainImage = $this->mainImage;
        if (!$mainImage) return asset('images/placeholder-product.jpg');
        
        return filter_var($mainImage->image_url, FILTER_VALIDATE_URL) 
            ? $mainImage->image_url 
            : asset('storage/' . $mainImage->image_url);
    }

    public function getThumbnailUrlAttribute()
    {
        $mainImage = $this->mainImage;
        if (!$mainImage) return asset('images/placeholder-thumbnail.jpg');
        
        $url = $mainImage->thumbnail_url ?: $mainImage->image_url;
        
        return filter_var($url, FILTER_VALIDATE_URL) 
            ? $url 
            : asset('storage/' . $url);
    }

    public function hasVariants()
    {
        return $this->type === 'configurable' && $this->variants()->exists();
    }

    public function getAvailableVariants()
    {
        if (!$this->hasVariants()) {
            return collect();
        }

        return $this->variants()->with('inventory')->get()->filter(function ($variant) {
            return $variant->isInStock();
        });
    }

    public function getAttributeOptions($attributeCode)
    {
        return $this->variants()
            ->with('attributeValues.attribute')
            ->get()
            ->flatMap(function ($variant) use ($attributeCode) {
                return $variant->attributeValues->filter(function ($value) use ($attributeCode) {
                    return $value->attribute->code === $attributeCode;
                });
            })
            ->unique('id')
            ->values();
    }
    public function canUserReview($userId)
    {
        if (!$userId) return false;
        
        // Already reviewed?
        if ($this->reviews()->where('user_id', $userId)->exists()) {
            return false;
        }
        
        // Has bought the product?
        $user = User::find($userId);
        return $user ? $user->hasOrderedProduct($this->id) : false;
    }
}
