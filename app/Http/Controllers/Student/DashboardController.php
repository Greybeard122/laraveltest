<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class DashboardController extends Controller
{
    public function index()
    {
        $student = Auth::guard('student')->user();
        $schedules = $student->schedules;
        
        return view('student.dashboard', [
            'student' => $student,  // Change $user to $student
            'schedules' => $schedules
        ]);
    }
    
}