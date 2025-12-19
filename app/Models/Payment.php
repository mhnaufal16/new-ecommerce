<?php
// app/Models/Payment.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'payment_method',
        'payment_gateway',
        'transaction_id',
        'transaction_status',
        'amount',
        'fee',
        'currency',
        'payment_details',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'fee' => 'decimal:2',
        'payment_details' => 'array',
        'paid_at' => 'datetime',
    ];

    protected $attributes = [
        'transaction_status' => 'pending',
        'currency' => 'IDR',
        'fee' => 0,
    ];

    // RELATIONSHIPS
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // SCOPES
    public function scopePending($query)
    {
        return $query->where('transaction_status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->whereIn('transaction_status', ['capture', 'settlement']);
    }

    public function scopeFailed($query)
    {
        return $query->whereIn('transaction_status', ['deny', 'cancel', 'expire', 'failure']);
    }

    // CUSTOM METHODS
    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public function getFormattedFeeAttribute()
    {
        return 'Rp ' . number_format($this->fee, 0, ',', '.');
    }

    public function getNetAmountAttribute()
    {
        return $this->amount - $this->fee;
    }

    public function getFormattedNetAmountAttribute()
    {
        return 'Rp ' . number_format($this->net_amount, 0, ',', '.');
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Menunggu',
            'capture' => 'Terkonfirmasi',
            'settlement' => 'Berhasil',
            'deny' => 'Ditolak',
            'cancel' => 'Dibatalkan',
            'expire' => 'Kedaluwarsa',
            'failure' => 'Gagal',
        ];

        return $labels[$this->transaction_status] ?? $this->transaction_status;
    }

    public function isSuccessful()
    {
        return in_array($this->transaction_status, ['capture', 'settlement']);
    }

    public function isPending()
    {
        return $this->transaction_status === 'pending';
    }

    public function isFailed()
    {
        return in_array($this->transaction_status, ['deny', 'cancel', 'expire', 'failure']);
    }

    public function markAsPaid($transactionId = null, $details = null)
    {
        $this->update([
            'transaction_status' => 'settlement',
            'transaction_id' => $transactionId ?: $this->transaction_id,
            'payment_details' => $details ?: $this->payment_details,
            'paid_at' => now(),
        ]);

        return $this;
    }

    public function markAsFailed($reason = null)
    {
        $this->update([
            'transaction_status' => 'failure',
            'payment_details' => array_merge(
                $this->payment_details ?? [],
                ['failure_reason' => $reason]
            ),
        ]);

        return $this;
    }

    public function getPaymentMethodLabelAttribute()
    {
        $labels = [
            'bank_transfer' => 'Transfer Bank',
            'credit_card' => 'Kartu Kredit',
            'debit_card' => 'Kartu Debit',
            'qris' => 'QRIS',
            'gopay' => 'GoPay',
            'ovo' => 'OVO',
            'dana' => 'DANA',
            'shopeepay' => 'ShopeePay',
            'alfamart' => 'Alfamart',
            'indomaret' => 'Indomaret',
        ];

        return $labels[$this->payment_method] ?? $this->payment_method;
    }
}