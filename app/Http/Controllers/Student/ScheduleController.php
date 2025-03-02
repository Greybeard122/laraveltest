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
    /**
     * Show the schedule request form.
     */
    public function create()
    {
        $files = File::where('is_available', true)->get(); // Retrieve only available files
        $schoolYears = SchoolYear::with('semesters')->orderBy('year', 'desc')->get();
        $semesters = Semester::all();

        return view('student.schedules.create', compact('files', 'schoolYears', 'semesters'));
    }

    /**
     * Store a new schedule request.
     */
    public function store(Request $request)
{
    // Ensure the student is authenticated using the correct guard
    $student = Auth::guard('student')->user();
    if (!$student) {
        return redirect()->route('student.dashboard')->with('error', 'You must be logged in as a student to submit a request.');
    }

    // Validate input
    $validated = $request->validate([
        'preferred_date' => ['required', 'date', 'after:today', function ($attribute, $value, $fail) {
            $dayOfWeek = Carbon::parse($value)->dayOfWeek;
            if ($dayOfWeek == Carbon::SATURDAY || $dayOfWeek == Carbon::SUNDAY) {
                $fail('Scheduling on weekends is not allowed. Please choose a weekday.');
            }
        }],
        'file_id' => 'required|exists:files,id',
        'preferred_time' => 'required|in:AM,PM',
        'reason' => 'required|string|max:255',
        'manual_school_year' => 'nullable|string|max:255',
        'manual_semester' => 'nullable|string|max:255',
        'copies' => 'required|integer|min:1',
        'school_year_id' => 'required|exists:school_years,id',
        'semester_id' => 'required|exists:semesters,id',
    ]);

    // Fetch the file
    $file = File::find($request->file_id);

    // Ensure manual school year and semester are provided if COR/COG is selected
    if (in_array(optional($file)->file_name, ['COR', 'COG']) && (!$request->manual_school_year || !$request->manual_semester)) {
        return back()->withErrors(['manual_school_year' => 'School year and semester are required for COR/COG requests.']);
    }

    // Manually attach student_id before saving
    $schedule = new Schedule();
    $schedule->student_id = $student->id; 
    $schedule->file_id = $request->file_id;
    $schedule->preferred_date = $request->preferred_date;
    $schedule->preferred_time = $request->preferred_time;
    $schedule->reason = $request->reason;
    $schedule->manual_school_year = $request->manual_school_year;
    $schedule->manual_semester = $request->manual_semester;
    $schedule->copies = $request->copies;
    $schedule->school_year_id = $request->school_year_id;
    $schedule->semester_id = $request->semester_id;
    $schedule->status = 'Pending';
    
    $schedule->save(); // 

    return redirect()->route('student.dashboard')->with('message', 'Schedule request submitted successfully.');
    
}

}
