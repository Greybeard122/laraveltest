<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Student;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
{
    $pendingRequests = Schedule::where('status', 'pending')->count();
    $todayAppointments = Schedule::whereDate('preferred_date', now())->count();

    // Add this weekly counts calculation
    $weeklyCounts = [
        'Monday' => 0, 'Tuesday' => 0, 'Wednesday' => 0,
        'Thursday' => 0, 'Friday' => 0
    ];

    $weeklySchedules = Schedule::whereBetween('preferred_date', 
        [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
        ->where('status', 'approved')
        ->get()
        ->groupBy(function ($schedule) {
            return Carbon::parse($schedule->preferred_date)->format('l'); // Get day name
        });

    foreach ($weeklySchedules as $day => $schedules) {
        if (isset($weeklyCounts[$day])) {
            $weeklyCounts[$day] = $schedules->count();
        }
    }

    // Fetch schedules for FullCalendar
    $schedules = Schedule::where('status', 'approved')
        ->get(['id', 'preferred_date', 'preferred_time']);

    // Format data for FullCalendar
    $events = $schedules->map(function ($schedule) {
        return [
            'id' => $schedule->id,
            'title' => 'Student Appointment', 
            'start' => $schedule->preferred_date . 'T' . $schedule->preferred_time,
            'color' => '#4A90E2'
        ];
    });

    return view('admin.dashboard', compact('pendingRequests', 'todayAppointments', 'events', 'weeklyCounts'));
}

}