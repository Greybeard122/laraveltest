<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class DashboardController extends Controller
{
    /* public function index()
    {
        $user = Auth::user();
    
        $schedules = Schedule::where('student_id', $student->id)
            ->where('status', '!=', 'completed') 
            ->orderBy('preferred_date', 'asc')
            ->get();
    
        return view('student.dashboard', compact('schedules'));
    } */
    public function index()
    {
        $user = Auth::user(); // Rename $student to $user for consistency

        $schedules = Schedule::where('student_id', $user->id)
            ->where('status', '!=', 'completed') 
            ->orderBy('preferred_date', 'asc')
            ->get();

        return view('student.dashboard', compact('user', 'schedules'));
    }
    
}