<?php
// app/Models/Inventory.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'variant_id',
        'sku',
        'quantity',
        'reserved_quantity',
        'low_stock_threshold',
        'stock_status',
        'warehouse_id',
        'location',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'reserved_quantity' => 'integer',
        'low_stock_threshold' => 'integer',
    ];

    // RELATIONSHIPS
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function logs()
    {
        return $this->hasMany(InventoryLog::class);
    }

    // SCOPES
    public function scopeInStock($query)
    {
        return $query->where('stock_status', 'in_stock')->where('quantity', '>', 0);
    }

    public function scopeLowStock($query)
    {
        return $query->where('quantity', '<=', $this->low_stock_threshold);
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('stock_status', 'out_of_stock')->orWhere('quantity', '<=', 0);
    }

    // CUSTOM METHODS
    public function getAvailableQuantityAttribute()
    {
        return max(0, $this->quantity - $this->reserved_quantity);
    }

    public function isLowStock()
    {
        return $this->quantity <= $this->low_stock_threshold;
    }

    public function isOutOfStock()
    {
        return $this->stock_status === 'out_of_stock' || $this->available_quantity <= 0;
    }

    public function increaseStock($quantity, $referenceType = null, $referenceId = null, $notes = null)
    {
        return $this->adjustStock($quantity, 'stock_in', $referenceType, $referenceId, $notes);
    }

    public function decreaseStock($quantity, $referenceType = null, $referenceId = null, $notes = null)
    {
        return $this->adjustStock(-$quantity, 'stock_out', $referenceType, $referenceId, $notes);
    }

    public function reserveStock($quantity)
    {
        if ($this->available_quantity < $quantity) {
            throw new \Exception("Insufficient stock available. Available: {$this->available_quantity}, Requested: {$quantity}");
        }

        $this->reserved_quantity += $quantity;
        $this->save();

        // Log the reservation
        $this->logs()->create([
            'action' => 'reserve',
            'quantity_change' => $quantity,
            'previous_quantity' => $this->quantity,
            'new_quantity' => $this->quantity,
            'notes' => "Reserved {$quantity} units",
        ]);

        return $this;
    }

    public function releaseStock($quantity)
    {
        if ($this->reserved_quantity < $quantity) {
            throw new \Exception("Cannot release more than reserved. Reserved: {$this->reserved_quantity}, Requested: {$quantity}");
        }

        $this->reserved_quantity -= $quantity;
        $this->save();

        // Log the release
        $this->logs()->create([
            'action' => 'release',
            'quantity_change' => -$quantity,
            'previous_quantity' => $this->quantity,
            'new_quantity' => $this->quantity,
            'notes' => "Released {$quantity} units from reservation",
        ]);

        return $this;
    }

    private function adjustStock($quantityChange, $action, $referenceType = null, $referenceId = null, $notes = null)
    {
        $previousQuantity = $this->quantity;
        $this->quantity += $quantityChange;
        
        // Update stock status based on new quantity
        if ($this->quantity <= 0) {
            $this->stock_status = 'out_of_stock';
        } elseif ($this->stock_status === 'out_of_stock') {
            $this->stock_status = 'in_stock';
        }
        
        $this->save();

        // Create log entry
        $this->logs()->create([
            'action' => $action,
            'quantity_change' => abs($quantityChange),
            'previous_quantity' => $previousQuantity,
            'new_quantity' => $this->quantity,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'notes' => $notes,
        ]);

        return $this;
    }

    // Model event to ensure either product_id or variant_id is set
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($inventory) {
            if (!($inventory->product_id xor $inventory->variant_id)) {
                throw new \Exception('Inventory must have either product_id OR variant_id, not both or neither');
            }
        });
    }
}