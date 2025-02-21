<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Schedule;
use Spatie\Permission\Traits\HasRoles;



class User extends Authenticatable
{
    use HasRoles;

    
    protected $fillable = [
        'name',
        'faculty_id',
        'email',
        'password',
        'is_admin'
    ];

    
    protected $hidden = [
        'password',
        'remember_token',
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
        ];
    }
    public function schedules()
    {
    return $this->hasMany(Schedule::class, 'student_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

}