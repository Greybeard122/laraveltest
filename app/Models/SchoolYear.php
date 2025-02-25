<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'file_id',
        'preferred_date',
        'preferred_time',
        'reason',
        'status',
        'semester_id',
        'school_year_id',  
    ];
    

    public function semesters()
    {
        return $this->hasMany(Semester::class);
    }
}
