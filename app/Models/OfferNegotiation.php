<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfferNegotiation extends Model
{
    protected $fillable = [
        'offer_id',
        'candidate_id',
        'proposed_salary',
        'candidate_notes',
        'status',
        'hr_notes',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'proposed_salary' => 'decimal:2',
            'reviewed_at' => 'datetime',
        ];
    }

    // Relationships
    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    public function candidate()
    {
        return $this->belongsTo(User::class, 'candidate_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
