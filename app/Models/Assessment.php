<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $fillable = [
        'interview_id',
        'interviewer_id',
        'technical_score',
        'technical_notes',
        'communication_skill',
        'problem_solving_score',
        'problem_solving_notes',
        'teamwork_potential',
        'overall_score',
        'strengths',
        'weaknesses',
        'additional_notes',
        'recommendation',
    ];

    protected function casts(): array
    {
        return [
            'technical_score' => 'integer',
            'problem_solving_score' => 'integer',
            'overall_score' => 'decimal:2',
        ];
    }

    // Relationships
    public function interview()
    {
        return $this->belongsTo(Interview::class);
    }

    public function interviewer()
    {
        return $this->belongsTo(User::class, 'interviewer_id');
    }
}
