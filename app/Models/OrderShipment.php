<?php
// app/Models/OrderShipment.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderShipment extends Model
{
    protected $fillable = [
        'order_id',
        'shipping_method',
        'courier_name',
        'courier_service',
        'tracking_number',
        'shipping_cost',
        'insurance_cost',
        'estimated_delivery',
        'shipped_at',
        'delivered_at',
        'notes',
    ];

    protected $casts = [
        'shipping_cost' => 'decimal:2',
        'insurance_cost' => 'decimal:2',
        'estimated_delivery' => 'date',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    // RELATIONSHIPS
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // CUSTOM METHODS
    public function getFormattedShippingCostAttribute()
    {
        return 'Rp ' . number_format($this->shipping_cost, 0, ',', '.');
    }

    public function getTotalCostAttribute()
    {
        return $this->shipping_cost + $this->insurance_cost;
    }

    public function getFormattedTotalCostAttribute()
    {
        return 'Rp ' . number_format($this->total_cost, 0, ',', '.');
    }

    public function getStatusAttribute()
    {
        if ($this->delivered_at) {
            return 'delivered';
        } elseif ($this->shipped_at) {
            return 'shipped';
        } else {
            return 'pending';
        }
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Menunggu Pengiriman',
            'shipped' => 'Telah Dikirim',
            'delivered' => 'Telah Diterima',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function markAsShipped()
    {
        $this->update(['shipped_at' => now()]);
        return $this;
    }

    public function markAsDelivered()
    {
        $this->update(['delivered_at' => now()]);
        return $this;
    }

    public function getTrackingUrlAttribute()
    {
        $couriers = [
            'jne' => 'https://www.jne.co.id/id/tracking/trace',
            'tiki' => 'https://www.tiki.id/id/tracking',
            'pos' => 'https://www.posindonesia.co.id/id/tracking',
            'sicepat' => 'https://www.sicepat.com/check-awb',
            'wahana' => 'https://www.wahana.com/',
            'anteraja' => 'https://anteraja.id/tracking',
        ];

        $url = $couriers[strtolower($this->courier_name)] ?? null;
        
        if ($url && $this->tracking_number) {
            return $url . '?no=' . $this->tracking_number;
        }
        
        return $url;
    }
}