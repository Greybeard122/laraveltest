<?php

namespace App\Http\Controllers\Student;

use Carbon\Carbon;
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
        'preferred_date' => ['required', 'date', 'after:today', function ($attribute, $value, $fail) {
            $dayOfWeek = \Carbon\Carbon::parse($value)->dayOfWeek;
            if ($dayOfWeek == \Carbon\Carbon::SATURDAY || $dayOfWeek == \Carbon\Carbon::SUNDAY) {
                $fail('Scheduling on weekends is not allowed. Please choose a weekday.');
            }
        }],
        'file_id' => 'nullable|exists:files,id',
        'preferred_time' => 'required',
        'reason' => 'required|string|max:255',
        'manual_school_year' => 'nullable|string|max:255',
        'manual_semester' => 'nullable|string|max:255',
        'copies' => 'required|integer|min:1',
        'school_year_id' => 'nullable|exists:school_years,id',
        'semester_id' => 'required|exists:semesters,id',
    ]);

    // Fetch the file name
    $file = \App\Models\File::find($request->file_id);

    // Ensure manual school year and semester are provided if COR/COG is selected
    if (in_array(optional($file)->file_name, ['COR', 'COG']) && (!$request->manual_school_year || !$request->manual_semester)) {
        return back()->withErrors(['manual_school_year' => 'School year and semester are required for COR/COG requests.']);
    }

    // Create the schedule record
    \App\Models\Schedule::create($validated);

    return back()->with('success', 'Schedule request submitted successfully.');
}


}