<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    public const SCHEDULE_BLOCKING_STATUSES = ['scheduled'];

    public const MAX_DURATION_MINUTES = 480;

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

     
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'scheduled')
                    ->where('scheduled_at', '>', now());
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public static function hasScheduleConflict(
        int $interviewerId,
        Carbon $startsAt,
        int $duration,
        ?int $exceptInterviewId = null
    ): bool {
        $endsAt = $startsAt->copy()->addMinutes($duration);

        return static::query()
            ->where('interviewer_id', $interviewerId)
            ->whereIn('status', self::SCHEDULE_BLOCKING_STATUSES)
            ->when(
                $exceptInterviewId,
                fn ($query) => $query->whereKeyNot($exceptInterviewId)
            )
            ->where('scheduled_at', '<', $endsAt)
            ->where('scheduled_at', '>=', $startsAt->copy()->subMinutes(self::MAX_DURATION_MINUTES))
            ->get(['id', 'scheduled_at', 'duration'])
            ->contains(function (Interview $interview) use ($startsAt, $endsAt) {
                $existingEndsAt = $interview->scheduled_at
                    ->copy()
                    ->addMinutes((int) $interview->duration);

                return $interview->scheduled_at->lt($endsAt)
                    && $existingEndsAt->gt($startsAt);
            });
    }
}
