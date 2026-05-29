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

     
    public function users()
    {
        return $this->hasMany(User::class);
    }

     
    const SUPER_ADMIN = 'super_admin';
    const HR = 'hr';
    const INTERVIEWER = 'interviewer';
    const CANDIDATE = 'candidate';

    public static function internalNames(): array
    {
        return [
            self::SUPER_ADMIN,
            self::HR,
            self::INTERVIEWER,
        ];
    }

    public function scopeInternal($query)
    {
        return $query->whereIn('name', self::internalNames());
    }
}
