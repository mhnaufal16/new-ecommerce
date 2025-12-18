<?php
// app/Models/ProductAttribute.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'type',
        'is_filterable',
        'is_visible',
        'sort_order',
    ];

    protected $casts = [
        'is_filterable' => 'boolean',
        'is_visible' => 'boolean',
    ];

    // RELATIONSHIPS
    public function values()
    {
        return $this->hasMany(ProductAttributeValue::class);
    }

    public function variantAttributes()
    {
        return $this->hasMany(ProductVariantAttribute::class);
    }

    // SCOPES
    public function scopeFilterable($query)
    {
        return $query->where('is_filterable', true);
    }

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    // CUSTOM METHODS
    public function getValueOptions()
    {
        return $this->values()->orderBy('sort_order')->pluck('value', 'id');
    }
}