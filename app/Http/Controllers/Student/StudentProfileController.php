<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentProfileController extends Controller
{
    // Show the student's profile
    // In StudentProfileController.php
public function show()
{
    try {
        // Get the logged-in student
        $student = Auth::user();
        
        // Check if we actually have a student
        if (!$student) {
            \Log::error('No authenticated user found in profile show');
            return redirect()->route('login');
        }

        // Log successful data retrieval
        \Log::info('Profile data retrieved for student: ' . $student->id);
        
        return view('student.profile.show', compact('student'));
    } catch (\Exception $e) {
        // Log the specific error
        \Log::error('Error in profile show: ' . $e->getMessage());
        
        // Return a more graceful error response
        return back()->with('error', 'Unable to load profile. Please try again later.');
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
