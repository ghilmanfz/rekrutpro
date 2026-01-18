<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemConfig extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
    ];

    /**
     * Get config value by key
     */
    public static function get(string $key, $default = null)
    {
        $config = static::where('key', $key)->first();
        
        if (!$config) {
            return $default;
        }

        return match($config->type) {
            'boolean' => filter_var($config->value, FILTER_VALIDATE_BOOLEAN),
            'json' => json_decode($config->value, true),
            default => $config->value,
        };
    }

    /**
     * Set config value by key
     */
    public static function set(string $key, $value, string $type = 'string', ?string $description = null): void
    {
        $valueToStore = is_array($value) ? json_encode($value) : $value;

        static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $valueToStore,
                'type' => $type,
                'description' => $description,
            ]
        );
    }
}
