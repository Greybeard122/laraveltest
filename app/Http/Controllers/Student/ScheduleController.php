<?php

namespace App\Http\Controllers\Student;


use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function create()
    {
        $files = File::where('is_available', true)->get();
        return view('student.schedules.create', compact('files'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'file_id' => 'required|exists:files,id',
            'preferred_date' => 'required|date|after:today',
            'preferred_time' => 'required'
        ]);

        Schedule::create([
            'student_id' => Auth::id(),
            'file_id' => $validated['file_id'],
            'preferred_date' => $validated['preferred_date'],
            'preferred_time' => $validated['preferred_time'],
            'status' => 'pending'
        ]);

        return redirect()->route('student.dashboard')
            ->with('success', 'Schedule request submitted successfully');
    }
}