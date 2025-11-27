<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $fillable = [
        'division_id',
        'name',
        'code',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function jobPostings()
    {
        return $this->hasMany(JobPosting::class);
    }
}
