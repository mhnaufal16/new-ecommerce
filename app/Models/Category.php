<?php
// app/Models/Category.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'description',
        'image',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // RELATIONSHIPS
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_categories')
                    ->withPivot('is_primary')
                    ->withTimestamps();
    }

    public function primaryProducts()
    {
        return $this->belongsToMany(Product::class, 'product_categories')
                    ->wherePivot('is_primary', true)
                    ->withTimestamps();
    }

    public function couponCategories()
    {
        return $this->hasMany(CouponCategory::class);
    }

    // SCOPES
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeWithChildren($query)
    {
        return $query->with(['children' => function ($q) {
            $q->active()->orderBy('sort_order');
        }]);
    }

    // CUSTOM METHODS
    public function getBreadcrumbAttribute()
    {
        $breadcrumb = [];
        $current = $this;
        
        while ($current) {
            $breadcrumb[] = $current;
            $current = $current->parent;
        }
        
        return array_reverse($breadcrumb);
    }

    public function getAllChildrenIds()
    {
        $ids = [$this->id];
        
        foreach ($this->children as $child) {
            $ids = array_merge($ids, $child->getAllChildrenIds());
        }
        
        return $ids;
    }

    public function getProductCountAttribute()
    {
        return $this->products()->count();
    }
}