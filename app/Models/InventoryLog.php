<?php
// app/Models/InventoryLog.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InventoryLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_id',
        'action',
        'quantity_change',
        'previous_quantity',
        'new_quantity',
        'reference_type',
        'reference_id',
        'notes',
        'performed_by',
        'performed_at',
    ];

    protected $casts = [
        'quantity_change' => 'integer',
        'previous_quantity' => 'integer',
        'new_quantity' => 'integer',
        'performed_at' => 'datetime',
    ];

    // RELATIONSHIPS
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function performer()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    // SCOPES
    public function scopeStockIn($query)
    {
        return $query->where('action', 'stock_in');
    }

    public function scopeStockOut($query)
    {
        return $query->where('action', 'stock_out');
    }

    public function scopeAdjustment($query)
    {
        return $query->where('action', 'adjustment');
    }

    // CUSTOM METHODS
    public function getActionLabelAttribute()
    {
        $labels = [
            'stock_in' => 'Stok Masuk',
            'stock_out' => 'Stok Keluar',
            'adjustment' => 'Penyesuaian',
            'reserve' => 'Reservasi',
            'release' => 'Pelepasan Reservasi',
        ];

        return $labels[$this->action] ?? $this->action;
    }

    public function getReferenceAttribute()
    {
        if (!$this->reference_type || !$this->reference_id) {
            return null;
        }

        $modelClass = 'App\\Models\\' . ucfirst($this->reference_type);
        
        if (class_exists($modelClass)) {
            return $modelClass::find($this->reference_id);
        }

        return null;
    }
}