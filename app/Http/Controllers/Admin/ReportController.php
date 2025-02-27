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

    $schedules = Schedule::with(['student', 'file', 'schoolYear', 'semester'])
        ->when($request->search, function ($query) use ($request) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%');
            })->orWhereHas('file', function ($q) use ($request) {
                $q->where('file_name', 'like', '%' . $request->search . '%');
            });
        })
        ->when($request->date_from, fn($query) => $query->whereDate('preferred_date', '>=', $request->date_from))
        ->when($request->date_to, fn($query) => $query->whereDate('preferred_date', '<=', $request->date_to))
        ->when($request->file_id, fn($query) => $query->where('file_id', $request->file_id))
        ->when($request->status, fn($query) => $query->where('status', $request->status))
        ->when($request->school_year_id, fn($query) => $query->where('school_year_id', $request->school_year_id))
        ->when($request->semester_id, fn($query) => $query->where('semester_id', $request->semester_id))
        ->when($request->sort, function ($query) use ($request) {
            $query->orderBy($request->sort, $request->direction ?? 'asc');
        }, fn($query) => $query->orderBy('preferred_date', 'desc'))
        ->paginate(10);

    return view('admin.reports.index', compact('schedules', 'files', 'schoolYears', 'semesters'));
}


    public function studentReport(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        // Fetch school years and semesters for filters
        $schoolYears = SchoolYear::all();
        $semesters = Semester::all();

        // Apply filters if selected
        $studentSchedules = Schedule::with(['file', 'semester', 'schoolYear'])
            ->where('student_id', $id)
            ->when($request->school_year_id, function ($query) use ($request) {
                $query->where('school_year_id', $request->school_year_id);
            })
            ->when($request->semester_id, function ($query) use ($request) {
                $query->where('semester_id', $request->semester_id);
            })
            ->selectRaw('file_id, semester_id, school_year_id, COUNT(*) as request_count, MAX(created_at) as latest_request, status')
            ->groupBy('file_id', 'semester_id', 'school_year_id', 'status')
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
