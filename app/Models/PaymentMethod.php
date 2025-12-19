<?php
// app/Models/PaymentMethod.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'logo',
        'is_active',
        'sort_order',
        'config',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'config' => 'array',
    ];

    // SCOPES
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSorted($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // CUSTOM METHODS
    public function getConfigValue($key, $default = null)
    {
        return data_get($this->config, $key, $default);
    }

    public function isBankTransfer()
    {
        return $this->code === 'bank_transfer';
    }

    public function isEWallet()
    {
        return in_array($this->code, ['gopay', 'ovo', 'dana', 'shopeepay']);
    }

    public function isConvenienceStore()
    {
        return in_array($this->code, ['alfamart', 'indomaret']);
    }

    public function isQRIS()
    {
        return $this->code === 'qris';
    }
}