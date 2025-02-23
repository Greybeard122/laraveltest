<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentProfileController extends Controller
{
    /**
     * Show the student's profile
     */
    public function show()
    {
        $student = Auth::guard('student')->user();

        if (!$student) {
            abort(403, "Unauthorized Access");
        }

        return view('student.profile.show', compact('student'));
    }

    /**
     * Show the form to edit the student's profile
     */
    public function edit()
    {
        $student = Auth::guard('student')->user();

        if (!$student) {
            abort(403, "Unauthorized Access");
        }

        return view('student.profile.edit', compact('student'));
    }

    /**
     * Update the student's profile
     */
    public function update(Request $request)
{
    $student = Auth::guard('student')->user();

    if (!$student) {
        abort(403, "Unauthorized Access");
    }

    if (!($student instanceof \Illuminate\Database\Eloquent\Model)) {
        throw new \Exception("Invalid Student model instance.");
    }

    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:students,email,' . $student->id,
        'course' => 'required|string|max:255',
        'contact_number' => 'required|string|max:20',
        'password' => $request->filled('password') ? 'required|string|min:6|confirmed' : '',
    ]);

    if (!$request->filled('password')) {
        unset($validated['password']);
    } else {
        $validated['password'] = Hash::make($validated['password']);
    }

    $validated = array_intersect_key($validated, array_flip($student->getFillable()));

    if (!$student->exists) {
        throw new \Exception("Student record does not exist.");
    }

    $student->update($validated);

    return redirect()->route('student.profile.show')->with('message', 'Profile updated successfully');
}

}
