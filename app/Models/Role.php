<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];

    // Relationships
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Constants for roles
    const SUPER_ADMIN = 'super_admin';
    const HR = 'hr';
    const INTERVIEWER = 'interviewer';
    const CANDIDATE = 'candidate';
}
