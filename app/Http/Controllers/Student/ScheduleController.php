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
    $files = File::where('is_available', true)->get(); // Retrieve only available files
    $schoolYears = SchoolYear::with('semesters')->orderBy('year', 'desc')->get();
    $semesters = Semester::all();

    return view('student.schedules.create', compact('files', 'schoolYears', 'semesters'));
}



public function store(Request $request)
{
    $validated = $request->validate([
        'file_id' => 'nullable|exists:files,id',
        'preferred_date' => 'required|date|after:today',
        'preferred_time' => 'required',
        'reason' => 'required|string|max:255',
        'manual_school_year' => 'nullable|string|max:255',
        'manual_semester' => 'nullable|string|max:255',
        'copies' => 'required|integer|min:1'
    ]);

    Schedule::create([
        'student_id' => Auth::id(),
        'file_id' => $validated['file_id'],
        'preferred_date' => $validated['preferred_date'],
        'preferred_time' => $validated['preferred_time'],
        'reason' => $validated['reason'],
        'manual_school_year' => $validated['manual_school_year'],
        'manual_semester' => $validated['manual_semester'],
        'copies' => $validated['copies'],
        'status' => 'pending'
    ]);

    return redirect()->route('student.dashboard')
        ->with('success', 'Schedule request submitted successfully');
}
    
}