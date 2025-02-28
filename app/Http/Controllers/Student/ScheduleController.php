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
        'school_year_id' => 'nullable|exists:school_years,id',
        'semester_id' => 'nullable|exists:semesters,id',
        'copies' => 'required|integer|min:1'
    ]);

    // Get file name to check if it's COR or COG
    $file = File::find($validated['file_id']);
    $isCorCog = in_array(optional($file)->file_name, ['COR', 'COG']);

    Schedule::create([
        'student_id' => Auth::id(),
        'file_id' => $validated['file_id'],
        'preferred_date' => $validated['preferred_date'],
        'preferred_time' => $validated['preferred_time'],
        'reason' => $validated['reason'],
        'school_year_id' => $isCorCog ? null : $validated['school_year_id'],
        'semester_id' => $isCorCog ? null : $validated['semester_id'],
        'manual_school_year' => $isCorCog ? $validated['manual_school_year'] : null,
        'manual_semester' => $isCorCog ? $validated['manual_semester'] : null,
        'copies' => $validated['copies'],
        'status' => 'pending'
    ]);

    return redirect()->route('student.dashboard')->with('success', 'Schedule request submitted successfully');
} 
}