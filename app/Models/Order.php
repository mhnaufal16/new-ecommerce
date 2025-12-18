<?php
// app/Models/Order.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'customer_email',
        'customer_phone',
        'status',
        'payment_status',
        'shipping_status',
        'currency',
        'subtotal',
        'discount_amount',
        'tax_amount',
        'shipping_amount',
        'insurance_amount',
        'grand_total',
        'total_paid',
        'notes',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'insurance_amount' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'total_paid' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'pending',
        'payment_status' => 'pending',
        'shipping_status' => 'pending',
        'currency' => 'IDR',
        'discount_amount' => 0,
        'tax_amount' => 0,
        'shipping_amount' => 0,
        'insurance_amount' => 0,
        'total_paid' => 0,
    ];

    // RELATIONSHIPS
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function addresses()
    {
        return $this->hasMany(OrderAddress::class);
    }

    public function billingAddress()
    {
        return $this->hasOne(OrderAddress::class)->where('address_type', 'billing');
    }

    public function shippingAddress()
    {
        return $this->hasOne(OrderAddress::class)->where('address_type', 'shipping');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function shipments()
    {
        return $this->hasMany(OrderShipment::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function couponUsage()
    {
        return $this->hasOne(CouponUsage::class);
    }

    // SCOPES
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopeUnpaid($query)
    {
        return $query->where('payment_status', 'pending');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
    }

    // CUSTOM METHODS
    public static function generateOrderNumber()
    {
        $prefix = 'ORD';
        $date = now()->format('Ymd');
        $lastOrder = self::where('order_number', 'like', "{$prefix}{$date}%")
                        ->orderBy('id', 'desc')
                        ->first();

        if ($lastOrder) {
            $lastNumber = intval(substr($lastOrder->order_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "{$prefix}{$date}{$newNumber}";
    }

    public function getFormattedSubtotalAttribute()
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    public function getFormattedGrandTotalAttribute()
    {
        return 'Rp ' . number_format($this->grand_total, 0, ',', '.');
    }

    public function getFormattedTaxAmountAttribute()
    {
        return 'Rp ' . number_format($this->tax_amount, 0, ',', '.');
    }

    public function getFormattedShippingAmountAttribute()
    {
        return 'Rp ' . number_format($this->shipping_amount, 0, ',', '.');
    }

    public function getFormattedDiscountAmountAttribute()
    {
        return 'Rp ' . number_format($this->discount_amount, 0, ',', '.');
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Menunggu',
            'processing' => 'Diproses',
            'on_hold' => 'Ditahan',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            'refunded' => 'Dikembalikan',
            'failed' => 'Gagal',
            'shipped' => 'Dikirim',
            'delivered' => 'Terkirim',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function getPaymentStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Menunggu Pembayaran',
            'paid' => 'Lunas',
            'partially_paid' => 'Sebagian Dibayar',
            'refunded' => 'Dikembalikan',
            'failed' => 'Gagal',
        ];

        return $labels[$this->payment_status] ?? $this->payment_status;
    }

    public function getShippingStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Menunggu',
            'processing' => 'Diproses',
            'shipped' => 'Dikirim',
            'delivered' => 'Terkirim',
            'cancelled' => 'Dibatalkan',
        ];

        return $labels[$this->shipping_status] ?? $this->shipping_status;
    }

    public function getTotalItemsAttribute()
    {
        return $this->items->sum('quantity');
    }

    public function getUniqueProductsCountAttribute()
    {
        return $this->items->count();
    }

    public function updateStatus($newStatus)
    {
        $oldStatus = $this->status;
        $this->update(['status' => $newStatus]);

        // Log status change
        activity('order')
            ->performedOn($this)
            ->withProperties([
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ])
            ->log('Order status updated');

        return $this;
    }

    public function updatePaymentStatus($newStatus)
    {
        $oldStatus = $this->payment_status;
        $this->update(['payment_status' => $newStatus]);

        // Jika sudah paid, update total_paid
        if ($newStatus === 'paid') {
            $this->update(['total_paid' => $this->grand_total]);
        }

        // Log status change
        activity('payment')
            ->performedOn($this)
            ->withProperties([
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ])
            ->log('Payment status updated');

        return $this;
    }

    public function updateShippingStatus($newStatus)
    {
        $oldStatus = $this->shipping_status;
        $this->update(['shipping_status' => $newStatus]);

        // Log status change
        activity('shipping')
            ->performedOn($this)
            ->withProperties([
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ])
            ->log('Shipping status updated');

        return $this;
    }

    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'processing']);
    }

    public function canBeRefunded()
    {
        return $this->status === 'completed' && $this->payment_status === 'paid';
    }

    public function getRemainingAmountAttribute()
    {
        return max(0, $this->grand_total - $this->total_paid);
    }

    public function addPayment($amount, $paymentMethod, $transactionId = null, $details = null)
    {
        $payment = $this->payments()->create([
            'payment_method' => $paymentMethod,
            'amount' => $amount,
            'transaction_id' => $transactionId,
            'payment_details' => $details,
        ]);

        // Update total_paid
        $this->increment('total_paid', $amount);

        // Update payment status jika sudah lunas
        if ($this->total_paid >= $this->grand_total) {
            $this->updatePaymentStatus('paid');
        } elseif ($this->total_paid > 0) {
            $this->updatePaymentStatus('partially_paid');
        }

        return $payment;
    }

    public function addShipment($shippingMethod, $courierName, $courierService, $shippingCost, $trackingNumber = null)
    {
        return $this->shipments()->create([
            'shipping_method' => $shippingMethod,
            'courier_name' => $courierName,
            'courier_service' => $courierService,
            'shipping_cost' => $shippingCost,
            'tracking_number' => $trackingNumber,
            'estimated_delivery' => now()->addDays(3), // Default 3 hari
        ]);
    }

    public function markAsShipped($trackingNumber = null)
    {
        if ($this->shipments()->exists()) {
            $this->shipments()->update(['shipped_at' => now()]);
        }
        
        $this->updateShippingStatus('shipped');
        return $this;
    }

    public function markAsDelivered()
    {
        if ($this->shipments()->exists()) {
            $this->shipments()->update(['delivered_at' => now()]);
        }
        
        $this->updateShippingStatus('delivered');
        $this->updateStatus('completed');
        
        return $this;
    }
}