<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'type',
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
            $body = str_replace('{{'.$key.'}}', $value, $body);
        }
        return $body;
    }
}
