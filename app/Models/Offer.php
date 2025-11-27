<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = [
        'application_id',
        'offered_by',
        'position_title',
        'salary',
        'salary_currency',
        'salary_period',
        'benefits',
        'contract_type',
        'start_date',
        'end_date',
        'terms_and_conditions',
        'internal_notes',
        'status',
        'valid_until',
        'rejection_reason',
        'responded_at',
    ];

    protected function casts(): array
    {
        return [
            'salary' => 'decimal:2',
            'benefits' => 'array',
            'start_date' => 'date',
            'end_date' => 'date',
            'valid_until' => 'date',
            'responded_at' => 'datetime',
        ];
    }

    // Relationships
    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function offeredBy()
    {
        return $this->belongsTo(User::class, 'offered_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }
}
