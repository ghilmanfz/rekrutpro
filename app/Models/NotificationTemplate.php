<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'type',
        'channel',
        'event',
        'subject',
        'body',
        'available_placeholders',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'available_placeholders' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Replace placeholders with actual data
     */
    public function replacePlaceholders(array $data): string
    {
        $body = $this->body;
        foreach ($data as $key => $value) {
            // Replace both {{key}} and {{ key }} (with optional spaces)
            $body = preg_replace('/\{\{\s*' . preg_quote($key, '/') . '\s*\}\}/', $value ?? '', $body);
        }
        return $body;
    }
}
