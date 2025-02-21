<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\File;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function index(Request $request)
{
    $files = File::all();

    $schedules = Schedule::with(['student', 'file'])
        ->when($request->date_from, fn($query) => $query->whereDate('created_at', '>=', $request->date_from))
        ->when($request->date_to, fn($query) => $query->whereDate('created_at', '<=', $request->date_to))
        ->when($request->file_id, fn($query) => $query->where('file_id', $request->file_id))
        ->when($request->status, fn($query) => $query->where('status', $request->status))
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    return view('admin.schedules.index', compact('schedules', 'files'));
}

    public function weeklySchedules(Request $request)
    {
        $selectedDay = $request->query('day');

        $query = Schedule::with(['student', 'file'])
            ->whereBetween('preferred_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->where('status', 'approved')
            ->orderBy('preferred_date', 'asc')
            ->orderBy('preferred_time', 'asc');

        if ($selectedDay) {
            $query->whereDate('preferred_date', Carbon::parse($selectedDay));
        }

        $schedules = $query->paginate(10);

        return view('admin.schedules.weekly', compact('schedules', 'selectedDay'));
    }

    public function getSchedulesByDateRange(Request $request)
    {
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        
        $schedules = Schedule::with(['student', 'file'])
            ->whereBetween('preferred_date', [$startDate, $endDate])
            ->where('status', 'approved')
            ->get()
            ->map(function ($schedule) {
                return [
                    'id' => $schedule->id,
                    'title' => optional($schedule->file)->file_name ?? 'No File',
                    'studentName' => optional($schedule->student)->name ?? 'Unknown Student',
                    'date' => $schedule->preferred_date,
                    'timeSlot' => $schedule->preferred_time,
                    'status' => $schedule->status,
                    'remarks' => $schedule->remarks
                ];
            });

        return response()->json($schedules);
    }

    public function approve(Schedule $schedule)
    {
        $schedule->update([
            'status' => 'approved',
            'approved_at' => now()
        ]);
        return back()->with('success', 'Schedule approved successfully.');
    }

    public function reject(Schedule $schedule)
    {
        $schedule->update(['status' => 'rejected']);
        return back()->with('success', 'Schedule rejected successfully.');
    }

    public function pendingSchedules()
    {
        $schedules = Schedule::with(['student', 'file'])
            ->where('status', 'pending')
            ->orderBy('preferred_date', 'asc')
            ->paginate(10);

        return view('admin.schedules.pending', compact('schedules'));
    }

    public function todaySchedules()
    {
        $today = Carbon::today()->toDateString();
        
        $schedules = Schedule::with(['student', 'file'])
            ->whereDate('preferred_date', $today)
            ->orderBy('preferred_time', 'asc')
            ->paginate(10);

        return view('admin.schedules.today', compact('schedules'));
    }

   

    public function studentSchedules($studentId)
    {
        $schedules = Schedule::with('file')
            ->where('student_id', $studentId)
            ->orderBy('preferred_date', 'desc')
            ->paginate(10);

        // Count requests per file type for this student
        $requestCounts = Schedule::where('student_id', $studentId)
            ->selectRaw('file_id, COUNT(*) as total_requests')
            ->groupBy('file_id')
            ->with('file')
            ->get();

        return view('admin.schedules.student', compact('schedules', 'requestCounts'));
    }
}
