<?php

namespace App\Http\Controllers\Student;


use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Schedule;
use App\Models\SchoolYear;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function create()
{
    $files = File::where('is_available', true)->get();
    $schoolYears = SchoolYear::orderBy('year', 'desc')->get();
    $semesters = Semester::all();

    return view('student.schedules.create', compact('files', 'schoolYears', 'semesters'));
}

public function store(Request $request)
{
    $validated = $request->validate([
        'file_id' => 'required|exists:files,id',
        'preferred_date' => 'required|date|after:today',
        'preferred_time' => 'required',
        'reason' => 'required|string|max:255',
        'school_year_id' => 'required|exists:school_years,id',  // Changed from school_year
        'semester_id' => 'required|exists:semesters,id'         // Changed from semester
    ]);

    Schedule::create([
        'student_id' => Auth::id(),
        'file_id' => $validated['file_id'],
        'preferred_date' => $validated['preferred_date'],
        'preferred_time' => $validated['preferred_time'],
        'reason' => $validated['reason'],
        'school_year_id' => $validated['school_year_id'],  // Changed
        'semester_id' => $validated['semester_id'],        // Changed
        'status' => 'pending'
    ]);

    return redirect()->route('student.dashboard')
        ->with('success', 'Schedule request submitted successfully');
}

}