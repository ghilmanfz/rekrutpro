<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Application extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'application_code',
        'job_posting_id',
        'candidate_id',
        'candidate_snapshot',  
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
            'candidate_snapshot' => 'array',  
            'other_documents' => 'array',
            'reviewed_at' => 'datetime',
            'screening_passed_at' => 'datetime',
            'interview_scheduled_at' => 'datetime',
            'interview_passed_at' => 'datetime',
            'offered_at' => 'datetime',
            'hired_at' => 'datetime',
        ];
    }

     
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

     
    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

     
    protected function getSnapshotData()
    {
        if (!$this->candidate_snapshot) {
            return [];
        }
        
         
        if (is_string($this->candidate_snapshot)) {
            return json_decode($this->candidate_snapshot, true) ?? [];
        }
        
         
        return is_array($this->candidate_snapshot) ? $this->candidate_snapshot : [];
    }

    protected function normalizeEducationEntries($value, array $snapshot = []): array
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                $value = $decoded;
            } elseif (filled($value)) {
                return [[
                    'degree' => $value,
                    'major' => $snapshot['study_program'] ?? null,
                    'institution' => null,
                    'year' => null,
                ]];
            }
        }

        if (! is_array($value) || $value === []) {
            return [];
        }

        if (! array_is_list($value)) {
            return [$value];
        }

        return collect($value)
            ->map(fn ($entry) => is_array($entry) ? $entry : ['degree' => $entry])
            ->all();
    }

    protected function normalizeExperienceEntries($value, ?string $fallbackDuration = null): array
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                $value = $decoded;
            } elseif (filled($value)) {
                return [[
                    'position' => $value,
                    'description' => $value,
                    'company' => null,
                    'duration' => $fallbackDuration,
                ]];
            }
        }

        if (! is_array($value) || $value === []) {
            return [];
        }

        if (! array_is_list($value)) {
            return [$value];
        }

        return collect($value)
            ->map(fn ($entry) => is_array($entry) ? $entry : ['description' => $entry])
            ->all();
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
        return $snapshot['birth_date'] ?? $snapshot['date_of_birth'] ?? null;
    }

    public function getCandidateGenderAttribute()
    {
        $snapshot = $this->getSnapshotData();
        return $snapshot['gender'] ?? $this->candidate->gender ?? 'N/A';
    }

    public function getCandidateEducationAttribute()
    {
        $snapshot = $this->getSnapshotData();
        $education = $snapshot['education'] ?? $snapshot['education_level'] ?? null;

        return $this->normalizeEducationEntries($education, $snapshot);
    }

    public function getCandidateExperienceAttribute()
    {
        $snapshot = $this->getSnapshotData();
        $experience = $snapshot['experience'] ?? $snapshot['experience_description'] ?? null;

        return $this->normalizeExperienceEntries(
            $experience,
            $snapshot['experience_level'] ?? null
        );
    }

    public function getCandidateProfilePhotoAttribute()
    {
        $snapshot = $this->getSnapshotData();
        return $snapshot['profile_photo'] ?? $this->candidate->profile_photo ?? null;
    }


    public function getEducationLevelAttribute()
    {
        $snapshot = $this->getSnapshotData();

        return $snapshot['education_level']
            ?? data_get($this->normalizeEducationEntries($snapshot['education'] ?? null, $snapshot), '0.degree');
    }

    public function getStudyProgramAttribute()
    {
        $snapshot = $this->getSnapshotData();

        return $snapshot['study_program']
            ?? $snapshot['program_studi']
            ?? data_get($this->normalizeEducationEntries($snapshot['education'] ?? null, $snapshot), '0.major');
    }

    public function getExperienceLevelAttribute()
    {
        $snapshot = $this->getSnapshotData();

        return $snapshot['experience_level']
            ?? data_get($this->normalizeExperienceEntries($snapshot['experience'] ?? null), '0.duration');
    }

    public function getExpectedSalaryAttribute()
    {
        return $this->getSnapshotData()['expected_salary'] ?? null;
    }

    public function getAvailabilityAttribute()
    {
        return $this->getSnapshotData()['availability'] ?? null;
    }

    public function getSnapshotAtAttribute()
    {
        return $this->getSnapshotData()['snapshot_at'] ?? $this->created_at;
    }

    public function getSkillsAttribute()
    {
        return $this->getSnapshotData()['skills'] ?? null;
    }

    public function getCvPathAttribute()
    {
        return $this->cv_file;
    }

    public function getPortfolioPathAttribute()
    {
        return $this->portfolio_file;
    }

    public function getEducationAttribute()
    {
        return $this->education_level;
    }

    public function getExperienceAttribute()
    {
        return $this->experience_level;
    }

    public function getPhoneAttribute()
    {
        return $this->candidate_phone;
    }

    public function hasStoredCv(): bool
    {
        return filled($this->cv_file)
            && Storage::disk('public')->exists($this->cv_file);
    }

    public function hasStoredPortfolio(): bool
    {
        return filled($this->portfolio_file)
            && Storage::disk('public')->exists($this->portfolio_file);
    }

    public function getCandidateExperienceDescriptionAttribute()
    {
        $snapshot = $this->getSnapshotData();

        return $snapshot['experience_description']
            ?? data_get($this->candidate_experience, '0.description')
            ?? data_get($this->candidate_experience, '0.position');
    }

    public function getCurrentCandidateAddressAttribute()
    {
        return $this->candidate?->address;
    }

    public function getCurrentCandidateAddressChangedAttribute(): bool
    {
        return $this->candidate_address !== $this->candidate?->address;
    }

    public function getCurrentCandidateBirthDateAttribute()
    {
        return $this->candidate?->date_of_birth?->toDateString();
    }

    public function getCurrentCandidateBirthDateChangedAttribute(): bool
    {
        return $this->candidate_birth_date !== $this->candidate?->date_of_birth?->toDateString();
    }

    public function getCurrentCandidateGenderAttribute()
    {
        $currentGender = $this->candidate->gender ?? null;

        return filled($currentGender) ? $currentGender : $this->candidate_gender;
    }

    public function getCurrentCandidateGenderChangedAttribute(): bool
    {
        $currentGender = $this->candidate->gender ?? null;

        return filled($currentGender) && $this->candidate_gender !== $currentGender;
    }

    public function getCurrentCandidateEducationAttribute(): array
    {
        if (! filled($this->candidate?->education)) {
            return [];
        }

        return $this->normalizeEducationEntries($this->candidate->education, [
            'study_program' => $this->candidate->study_program,
        ]);
    }

    public function getCurrentCandidateExperienceAttribute(): array
    {
        if (! filled($this->candidate?->experience)) {
            return [];
        }

        return $this->normalizeExperienceEntries($this->candidate->experience);
    }

    


    public function hasProfileChangedSinceApply()
    {
        $snapshot = $this->getSnapshotData();
        $current = $this->candidate;

        if ($snapshot === [] || ! $current) {
            return false;
        }

        return ($snapshot['full_name'] ?? null) !== $current->name
            || ($snapshot['email'] ?? null) !== $current->email
            || ($snapshot['phone'] ?? null) !== $current->phone
            || ($snapshot['address'] ?? null) !== $current->address
            || $this->candidate_birth_date !== $current->date_of_birth?->toDateString()
            || $this->education_level !== $current->education
            || $this->study_program !== $current->study_program
            || $this->candidate_experience_description !== $current->experience
            || ($snapshot['skills'] ?? null) !== $current->skills;
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
