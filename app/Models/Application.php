<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'application_code',
        'job_posting_id',
        'candidate_id',
        'candidate_snapshot', // Snapshot data kandidat saat apply
        'cv_file',
        'cover_letter',
        'portfolio_file',
        'other_documents',
        'status',
        'status_notes',
        'rejection_reason',
        'reviewed_by',
        'reviewed_at',
        'screening_passed_at',
        'interview_scheduled_at',
        'interview_passed_at',
        'offered_at',
        'hired_at',
    ];

    protected function casts(): array
    {
        return [
            'candidate_snapshot' => 'array', // Cast JSON ke array
            'other_documents' => 'array',
            'reviewed_at' => 'datetime',
            'screening_passed_at' => 'datetime',
            'interview_scheduled_at' => 'datetime',
            'interview_passed_at' => 'datetime',
            'offered_at' => 'datetime',
            'hired_at' => 'datetime',
        ];
    }

    // Relationships
    public function jobPosting()
    {
        return $this->belongsTo(JobPosting::class);
    }

    public function candidate()
    {
        return $this->belongsTo(User::class, 'candidate_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function interviews()
    {
        return $this->hasMany(Interview::class);
    }

    public function offer()
    {
        return $this->hasOne(Offer::class);
    }

    // Scopes
    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    // Accessor methods untuk kemudahan akses data snapshot
    protected function getSnapshotData()
    {
        if (!$this->candidate_snapshot) {
            return [];
        }
        
        // Jika masih string, decode manual
        if (is_string($this->candidate_snapshot)) {
            return json_decode($this->candidate_snapshot, true) ?? [];
        }
        
        // Jika sudah array, return langsung
        return is_array($this->candidate_snapshot) ? $this->candidate_snapshot : [];
    }
    
    public function getCandidateNameAttribute()
    {
        $snapshot = $this->getSnapshotData();
        return $snapshot['full_name'] ?? $this->candidate->name ?? 'N/A';
    }

    public function getCandidateEmailAttribute()
    {
        $snapshot = $this->getSnapshotData();
        return $snapshot['email'] ?? $this->candidate->email ?? 'N/A';
    }

    public function getCandidatePhoneAttribute()
    {
        $snapshot = $this->getSnapshotData();
        return $snapshot['phone'] ?? $this->candidate->phone ?? 'N/A';
    }

    public function getCandidateAddressAttribute()
    {
        $snapshot = $this->getSnapshotData();
        return $snapshot['address'] ?? $this->candidate->address ?? 'N/A';
    }

    public function getCandidateBirthDateAttribute()
    {
        $snapshot = $this->getSnapshotData();
        return $snapshot['birth_date'] ?? null;
    }

    public function getCandidateGenderAttribute()
    {
        $snapshot = $this->getSnapshotData();
        return $snapshot['gender'] ?? $this->candidate->gender ?? 'N/A';
    }

    public function getCandidateEducationAttribute()
    {
        $snapshot = $this->getSnapshotData();
        return $snapshot['education'] ?? [];
    }

    public function getCandidateExperienceAttribute()
    {
        $snapshot = $this->getSnapshotData();
        return $snapshot['experience'] ?? [];
    }

    public function getCandidateProfilePhotoAttribute()
    {
        $snapshot = $this->getSnapshotData();
        return $snapshot['profile_photo'] ?? $this->candidate->profile_photo ?? null;
    }

    /**
     * Check apakah kandidat telah mengupdate profil setelah apply
     */
    public function hasProfileChangedSinceApply()
    {
        if (!$this->candidate_snapshot) {
            return false;
        }

        $snapshot = $this->candidate_snapshot;
        
        // Pastikan snapshot adalah array
        if (!is_array($snapshot)) {
            return false;
        }
        
        $current = $this->candidate;

        return ($snapshot['email'] ?? null) !== $current->email ||
               ($snapshot['phone'] ?? null) !== $current->phone ||
               ($snapshot['full_name'] ?? null) !== $current->name;
    }

    public function scopeScreeningPassed($query)
    {
        return $query->where('status', 'screening_passed');
    }

    public function scopeInterviewScheduled($query)
    {
        return $query->where('status', 'interview_scheduled');
    }

    public function scopeOffered($query)
    {
        return $query->where('status', 'offered');
    }

    public function scopeHired($query)
    {
        return $query->where('status', 'hired');
    }

    // Status constants
    const STATUS_SUBMITTED = 'submitted';
    const STATUS_SCREENING_PASSED = 'screening_passed';
    const STATUS_REJECTED_ADMIN = 'rejected_admin';
    const STATUS_INTERVIEW_SCHEDULED = 'interview_scheduled';
    const STATUS_INTERVIEW_PASSED = 'interview_passed';
    const STATUS_REJECTED_INTERVIEW = 'rejected_interview';
    const STATUS_OFFERED = 'offered';
    const STATUS_HIRED = 'hired';
    const STATUS_REJECTED_OFFER = 'rejected_offer';
    const STATUS_ARCHIVED = 'archived';
}
