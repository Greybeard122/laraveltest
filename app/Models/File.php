<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class File extends Model
{
    use HasFactory;

    protected $fillable = ['file_name', 'is_available'];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function user()
{
    return $this->belongsTo(User::class); // Assuming 'user_id' is the foreign key
}
}
