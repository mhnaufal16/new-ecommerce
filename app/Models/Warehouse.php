<?php
// app/Models/Warehouse.php (Tambahan jika diperlukan)
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'address',
        'city_id',
        'city_name',
        'province_id',
        'province_name',
        'phone',
        'email',
        'manager_name',
        'is_active',
        'capacity',
        'current_utilization',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'capacity' => 'decimal:2',
        'current_utilization' => 'decimal:2',
    ];

    // RELATIONSHIPS
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    // SCOPES
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // CUSTOM METHODS
    public function getUtilizationPercentageAttribute()
    {
        if ($this->capacity <= 0) {
            return 0;
        }
        
        return ($this->current_utilization / $this->capacity) * 100;
    }

    public function getAvailableCapacityAttribute()
    {
        return max(0, $this->capacity - $this->current_utilization);
    }

    public function updateUtilization()
    {
        $totalInventory = $this->inventories()->sum('quantity');
        $this->update(['current_utilization' => $totalInventory]);
        
        return $this;
    }

    public function isFull()
    {
        return $this->utilization_percentage >= 95;
    }

    public function hasAvailableSpace($additionalQuantity)
    {
        return $this->available_capacity >= $additionalQuantity;
    }
}