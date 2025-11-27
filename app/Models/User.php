<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'division_id',
        'phone',
        'address',
        'profile_photo',
        'is_active',
        'last_login_at',
        'otp_code',
        'otp_expires_at',
        'is_verified',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp_code',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'otp_expires_at' => 'datetime',
            'is_active' => 'boolean',
            'is_verified' => 'boolean',
        ];
    }

    // Relationships
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'candidate_id');
    }

    public function interviews()
    {
        return $this->hasMany(Interview::class, 'interviewer_id');
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class, 'interviewer_id');
    }

    public function createdJobPostings()
    {
        return $this->hasMany(JobPosting::class, 'created_by');
    }

    // Helper methods
    public function isSuperAdmin()
    {
        return $this->role && $this->role->name === Role::SUPER_ADMIN;
    }

    public function isHR()
    {
        return $this->role && $this->role->name === Role::HR;
    }

    public function isInterviewer()
    {
        return $this->role && $this->role->name === Role::INTERVIEWER;
    }

    public function isCandidate()
    {
        return $this->role && $this->role->name === Role::CANDIDATE;
    }

}
