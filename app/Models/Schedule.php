<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
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
        'remarks',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function file()
    {
        return $this->belongsTo(File::class);
    }
    
public function schoolYear()
{
    return $this->belongsTo(SchoolYear::class, 'school_year_id');
}

public function semester()
{
    return $this->belongsTo(Semester::class, 'semester_id');
}
}
