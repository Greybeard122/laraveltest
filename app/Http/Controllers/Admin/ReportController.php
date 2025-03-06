<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\File;
use App\Models\Student;
use App\Models\SchoolYear;
use App\Models\Semester;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
{
    $files = File::all();
    $schoolYears = SchoolYear::all();
    $semesters = Semester::all();

    $query = Schedule::with(['student', 'file', 'schoolYear'])
        ->join('semesters', 'schedules.semester_id', '=', 'semesters.id')
        ->select('schedules.*', 'semesters.name as semester_name');

    //  Search Student Name
    if ($request->search) {
        $query->whereHas('student', function ($q) use ($request) {
            $q->where('first_name', 'like', "%{$request->search}%")
              ->orWhere('last_name', 'like', "%{$request->search}%");
        });
    }

    // Apply Filters
    $query->when($request->file_id, fn($q) => $q->where('schedules.file_id', $request->file_id))
          ->when($request->school_year_id, fn($q) => $q->where('schedules.school_year_id', $request->school_year_id))
          ->when($request->semester_id, fn($q) => $q->where('schedules.semester_id', $request->semester_id))
          ->when($request->status, fn($q) => $q->where('schedules.status', $request->status));

    // Sorting & Pagination
    $schedules = $query->orderBy('schedules.preferred_date', 'desc')->paginate(10)->withQueryString();

    return view('admin.reports.index', compact('schedules', 'files', 'schoolYears', 'semesters'));
}

public function studentReport(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $schoolYears = SchoolYear::all();
        $semesters = Semester::all();

        $studentSchedules = Schedule::with(['file', 'semester', 'schoolYear'])
            ->where('student_id', $id)
            ->when($request->school_year_id, fn($query) => $query->where('school_year_id', $request->school_year_id))
            ->when($request->semester_id, fn($query) => $query->where('semester_id', $request->semester_id))
            ->when($request->manual_school_year, fn($query) => $query->where('manual_school_year', 'LIKE', "%{$request->manual_school_year}%"))
            ->when($request->manual_semester, fn($query) => $query->where('manual_semester', 'LIKE', "%{$request->manual_semester}%"))
            ->when($request->copies, fn($query) => $query->where('copies', $request->copies))
            ->selectRaw('file_id, semester_id, school_year_id, manual_school_year, manual_semester, copies, COUNT(*) as request_count, MAX(created_at) as latest_request, status')
            ->groupBy('file_id', 'semester_id', 'school_year_id', 'manual_school_year', 'manual_semester', 'copies', 'status')
            ->paginate(10);

        return view('admin.reports.student', compact('student', 'studentSchedules', 'schoolYears', 'semesters'));
    }

    public function archivedReports()
    {
        $sevenDaysAgo = now()->subDays(7);

        $archivedSchedules = Schedule::with(['student', 'file'])
            ->where('created_at', '<', $sevenDaysAgo)
            ->paginate(10);

        return view('admin.reports.archived', compact('archivedSchedules'));
    }
    public function todayAppointments()
{
    $today = Carbon::today(); // Get the current date

    $schedules = Schedule::with(['student', 'file'])
        ->whereDate('preferred_date', $today) // Filter for today's date
        ->where('status', 'approved') // Only approved schedules
        ->orderBy('preferred_time', 'asc') // Sort by time
        ->get();

    return view('admin.reports.today', compact('schedules'));
}
}