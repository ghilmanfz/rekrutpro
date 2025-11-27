<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    protected $fillable = [
        'application_id',
        'interviewer_id',
        'scheduled_by',
        'scheduled_at',
        'duration',
        'interview_type',
        'location',
        'notes',
        'status',
        'cancellation_reason',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
        ];
    }

    // Relationships
    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function interviewer()
    {
        return $this->belongsTo(User::class, 'interviewer_id');
    }

    public function scheduledBy()
    {
        return $this->belongsTo(User::class, 'scheduled_by');
    }

    public function scheduler()
    {
        return $this->belongsTo(User::class, 'scheduled_by');
    }

    public function assessment()
    {
        return $this->hasOne(Assessment::class);
    }

    // Scopes
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'scheduled')
                    ->where('scheduled_at', '>', now());
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
