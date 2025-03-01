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
}