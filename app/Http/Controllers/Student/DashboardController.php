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
    
        $today = now()->toDateString(); // Get today's date
    
        // Fetch upcoming schedules and today's schedules (exclude past ones)
        $schedules = Schedule::where('student_id', $student->id)
            ->whereDate('preferred_date', '>=', $today) // Include today and future
            ->orderBy('preferred_date', 'asc')
            ->get();
    
        // Fetch completed schedules (only past requests)
        $completedSchedules = Schedule::where('student_id', $student->id)
            ->whereDate('preferred_date', '<', $today) // Only past requests
            ->orderBy('preferred_date', 'desc')
            ->get();
    
        return view('student.dashboard', compact('schedules', 'completedSchedules'));
    }
    public function studentHistory()
    {
        $student = Auth::guard('student')->user();
    
        // Fetch completed schedules (past requests)
        $completedSchedules = Schedule::where('student_id', $student->id)
            ->whereDate('preferred_date', '<', now()) // Only past requests
            ->orderBy('preferred_date', 'desc')
            ->get();
    
        return view('student.history', compact('completedSchedules'));
    }
}