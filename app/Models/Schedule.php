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
        'status',
        'approved_at',
        'remarks'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function file()
    {
        return $this->belongsTo(File::class);
    }
}
