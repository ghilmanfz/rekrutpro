<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobPosting extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'position_id',
        'division_id',
        'location_id',
        'created_by',
        'title',
        'description',
        'requirements',
        'responsibilities',
        'benefits',
        'quota',
        'employment_type',
        'experience_level',
        'salary_min',
        'salary_max',
        'salary_currency',
        'published_at',
        'closed_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'date',
            'closed_at' => 'date',
            'salary_min' => 'decimal:2',
            'salary_max' => 'decimal:2',
        ];
    }

    // Relationships
    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'active')
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }
}
