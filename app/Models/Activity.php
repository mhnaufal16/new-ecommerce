<?php
// app/Models/Activity.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'subject_type',
        'subject_id',
        'description',
        'ip_address',
        'user_agent',
    ];

    // RELATIONSHIPS
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        return $this->morphTo();
    }

    // SCOPES
    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // CUSTOM METHODS
    public function getSubjectNameAttribute()
    {
        if (!$this->subject) {
            return 'Unknown';
        }

        if (method_exists($this->subject, 'getActivitySubjectName')) {
            return $this->subject->getActivitySubjectName();
        }

        return class_basename($this->subject_type) . ' #' . $this->subject_id;
    }

    public function getTypeLabelAttribute()
    {
        $labels = [
            'login' => 'Login',
            'logout' => 'Logout',
            'order' => 'Pesanan',
            'payment' => 'Pembayaran',
            'product' => 'Produk',
            'user' => 'Pengguna',
            'category' => 'Kategori',
            'coupon' => 'Kupon',
            'review' => 'Ulasan',
        ];

        return $labels[$this->type] ?? $this->type;
    }

    public static function log($type, $subject, $description = null, $user = null)
    {
        return self::create([
            'user_id' => $user ? (is_object($user) ? $user->id : $user) : auth()->id(),
            'type' => $type,
            'subject_type' => get_class($subject),
            'subject_id' => $subject->id,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}