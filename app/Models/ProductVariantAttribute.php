<?php
// app/Models/ProductVariantAttribute.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariantAttribute extends Model
{
    protected $table = 'product_variant_attributes';
    
    protected $fillable = [
        'variant_id',
        'attribute_id',
        'attribute_value_id',
    ];

    public $timestamps = false;

    // RELATIONSHIPS
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function attribute()
    {
        return $this->belongsTo(ProductAttribute::class);
    }

    public function attributeValue()
    {
        return $this->belongsTo(ProductAttributeValue::class);
    }
}