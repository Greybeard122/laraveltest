<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentProfileController extends Controller
{
    public function show()
    {
        try {
            // Debug authentication status
            \Log::info('Auth check: ' . (Auth::check() ? 'true' : 'false'));
            
            $student = Auth::user();
            \Log::info('Student data: ' . json_encode($student));
            
            // Force view check
            if (!view()->exists('student.profile.show')) {
                \Log::error('View student.profile.show does not exist');
                return redirect()->route('dashboard')->with('error', 'Profile view not found');
            }
            
            return view('student.profile.show', compact('student'));
        } catch (\Exception $e) {
            \Log::error('Profile show error: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', $e->getMessage());
        }
    }

    // Show the form to edit the student's profile
    public function edit()
    {
        // Get the logged-in student
        $student = Auth::user();

        return view('student.profile.edit', compact('student'));
    }

    // Update the student's profile
    public function update(Request $request)
{
    // Get the logged-in student
    $student = Auth::user();

    // Define the validation rules
    $validationRules = [
        'student_id' => 'required|unique:students,student_id,' . $student->id,
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required|email|unique:students,email,' . $student->id,
        'course' => 'required',
        'contact_number' => 'required',
    ];

    // If a password is provided, add password validation rules
    if ($request->filled('password')) {
        $validationRules['password'] = 'required|min:6|confirmed'; 
    }

    // Validate the input
    $validated = $request->validate($validationRules);

    // If password is provided, hash it before updating
    if ($request->filled('password')) {
        $validated['password'] = Hash::make($validated['password']);
    }

    // Update the student's details, excluding student_id which is not editable
    $student->update($validated);

    return redirect()->route('student.profile.show')->with('message', 'Profile updated successfully');
}

}
