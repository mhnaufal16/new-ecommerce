<?php
// app/Models/ProductAttributeValue.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductAttributeValue extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'attribute_id',
        'value',
        'color_code',
        'image_url',
        'sort_order',
    ];

    // RELATIONSHIPS
    public function attribute()
    {
        return $this->belongsTo(ProductAttribute::class);
    }

    public function variantAttributes()
    {
        return $this->hasMany(ProductVariantAttribute::class);
    }

    // CUSTOM METHODS
    public function getDisplayValueAttribute()
    {
        if ($this->attribute->type === 'color' && $this->color_code) {
            return "<span class='color-swatch' style='background-color: {$this->color_code}' title='{$this->value}'></span>";
        }
        
        return $this->value;
    }
}