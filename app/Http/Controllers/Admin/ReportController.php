<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\File;
use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
{
    $files = File::all();
    
    $schedules = Schedule::with(['student', 'file'])
        ->when($request->search, function ($query) use ($request) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%');
            });
        })
        ->when($request->date_from, fn($query) => $query->whereDate('preferred_date', '>=', $request->date_from))
        ->when($request->date_to, fn($query) => $query->whereDate('preferred_date', '<=', $request->date_to))
        ->when($request->file_id, fn($query) => $query->where('file_id', $request->file_id))
        ->when($request->status, fn($query) => $query->where('status', $request->status))
        ->orderBy('preferred_date', 'desc')
        ->paginate(10);

    return view('admin.reports.index', compact('schedules', 'files'));
}



    public function studentReport($studentId)
    {
        $student = Student::findOrFail($studentId);

        $studentSchedules = Schedule::where('student_id', $studentId)
            ->join('files', 'schedules.file_id', '=', 'files.id')
            ->select('schedules.*', 'files.file_name')
            ->orderBy('schedules.created_at', 'desc')
            ->paginate(10);

        return view('admin.reports.student', compact('student', 'studentSchedules'));
    }

    public function archivedReports() // ✅ Add this function if needed
    {
        $sevenDaysAgo = now()->subDays(7);

        $archivedSchedules = Schedule::with(['student', 'file'])
            ->where('created_at', '<', $sevenDaysAgo)
            ->paginate(10);

        return view('admin.reports.archived', compact('archivedSchedules'));
    }
}
