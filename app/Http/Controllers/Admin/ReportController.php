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

    // Apply filters
    if ($request->file_id) {
        $query->where('schedules.file_id', $request->file_id);
    }
    
    if ($request->school_year_id) {
        $query->where('schedules.school_year_id', $request->school_year_id);
    }
    
    if ($request->semester_id) {
        $query->where('schedules.semester_id', $request->semester_id);
    }
    
    // Date range filter
    if ($request->start_date) {
        $query->whereDate('schedules.preferred_date', '>=', $request->start_date);
    }
    
    if ($request->end_date) {
        $query->whereDate('schedules.preferred_date', '<=', $request->end_date);
    }
    
    // Sorting
    $sort = $request->sort ?? 'preferred_date';
    $direction = $request->direction ?? 'desc';
    $query->orderBy("schedules.{$sort}", $direction);
    
    $schedules = $query->paginate(10)->withQueryString();

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
}