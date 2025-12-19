<?php
// app/Models/Notification.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    // RELATIONSHIPS
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // SCOPES
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }

    // CUSTOM METHODS
    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
        
        return $this;
    }

    public function markAsUnread()
    {
        $this->update([
            'is_read' => false,
            'read_at' => null,
        ]);
        
        return $this;
    }

    public function getTypeLabelAttribute()
    {
        $labels = [
            'order' => 'Pesanan',
            'payment' => 'Pembayaran',
            'shipping' => 'Pengiriman',
            'product' => 'Produk',
            'promotion' => 'Promosi',
            'system' => 'Sistem',
            'account' => 'Akun',
        ];

        return $labels[$this->type] ?? $this->type;
    }

    public function getIconAttribute()
    {
        $icons = [
            'order' => '🛒',
            'payment' => '💰',
            'shipping' => '🚚',
            'product' => '📦',
            'promotion' => '🎉',
            'system' => '⚙️',
            'account' => '👤',
        ];

        return $icons[$this->type] ?? '📢';
    }

    public static function send($userId, $type, $title, $message, $data = null)
    {
        return self::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'is_read' => false,
        ]);
    }

    public static function sendToAll($type, $title, $message, $data = null, $userIds = null)
    {
        $users = $userIds 
            ? User::whereIn('id', $userIds)->get()
            : User::all();

        $notifications = [];
        foreach ($users as $user) {
            $notifications[] = [
                'user_id' => $user->id,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'data' => $data,
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (!empty($notifications)) {
            self::insert($notifications);
        }

        return count($notifications);
    }
}