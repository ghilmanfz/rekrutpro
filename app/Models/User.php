<?php

namespace App\Models;

 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable
{
     
    use HasFactory, Notifiable;

    




    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'division_id',
        'phone',
        'address',
        'profile_photo',
        'cv_path',
        'is_active',
        'last_login_at',
        'otp_code',
        'otp_expires_at',
        'is_verified',
        'date_of_birth',
        'education',
        'experience',
        'skills',
        'linkedin_url',
        'github_url',
        'portfolio_url',
        'registration_step',
        'registration_completed',
    ];

    




    protected $hidden = [
        'password',
        'remember_token',
        'otp_code',
    ];

    




    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'last_login_at' => 'datetime',
            'otp_expires_at' => 'datetime',
            'is_active' => 'boolean',
            'is_verified' => 'boolean',
            'registration_completed' => 'boolean',
        ];
    }

     
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

    public function scopeWithRoleName($query, string $roleName)
    {
        return $query->whereHas('role', fn ($roleQuery) => $roleQuery->where('name', $roleName));
    }

     
    protected static function booted()
    {
         
        static::creating(function ($user) {
            if (empty($user->role_id)) {
                $candidateRole = Role::where('name', 'candidate')->first();
                if ($candidateRole) {
                    $user->role_id = $candidateRole->id;
                    \Log::info('Auto-assigned candidate role to user during creation', ['email' => $user->email]);
                }
            }
            
             
            if (!empty($user->role_id)) {
                $roleName = Role::whereKey($user->role_id)->value('name');

                if (in_array($roleName, Role::internalNames(), true)) {
                    $user->registration_completed = true;
                    $user->is_verified = true;
                    $user->is_active = true;
                    \Log::info('Auto-completed registration for internal user', [
                        'email' => $user->email, 
                        'role_id' => $user->role_id
                    ]);
                }
            }
        });

         
        static::saved(function ($user) {
            if (empty($user->role_id)) {
                $candidateRole = Role::where('name', 'candidate')->first();
                if ($candidateRole) {
                    DB::table('users')->where('id', $user->id)->update(['role_id' => $candidateRole->id]);
                    \Log::warning('Fixed NULL role_id after save', ['user_id' => $user->id, 'email' => $user->email]);
                }
            }
        });
    }

     
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
