<?php
// app/Models/Setting.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    // SCOPES
    public function scopeGroup($query, $group)
    {
        return $query->where('group', $group);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    // CUSTOM METHODS
    public function getValueAttribute($value)
    {
        switch ($this->type) {
            case 'number':
                return is_numeric($value) ? floatval($value) : $value;
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'array':
            case 'json':
                return json_decode($value, true) ?: [];
            default:
                return $value;
        }
    }

    public function setValueAttribute($value)
    {
        switch ($this->type) {
            case 'number':
                $this->attributes['value'] = is_numeric($value) ? $value : 0;
                break;
            case 'boolean':
                $this->attributes['value'] = $value ? 'true' : 'false';
                break;
            case 'array':
            case 'json':
                $this->attributes['value'] = json_encode($value);
                break;
            default:
                $this->attributes['value'] = $value;
        }
    }

    public static function getValue($group, $key, $default = null)
    {
        $setting = self::where('group', $group)
                      ->where('key', $key)
                      ->first();

        return $setting ? $setting->value : $default;
    }

    public static function setValue($group, $key, $value, $type = 'string', $isPublic = false)
    {
        return self::updateOrCreate(
            ['group' => $group, 'key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'is_public' => $isPublic,
            ]
        );
    }

    public static function getGroup($group)
    {
        return self::where('group', $group)
                  ->get()
                  ->mapWithKeys(function ($item) {
                      return [$item->key => $item->value];
                  })
                  ->toArray();
    }
}