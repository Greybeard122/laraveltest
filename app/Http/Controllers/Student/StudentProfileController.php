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
    public function show()
    {
        $student = Auth::guard('student')->user();
        return view('student.profile.show', compact('student'));
    }

    // Show the form to edit the student's profile
    public function edit()
    {
        $student = Auth::guard('student')->user();
        return view('student.profile.edit', compact('student'));
    }

    // Update the student's profile
    public function update(Request $request)
    {
        $student = Student::find(Auth::guard('student')->id());

        $validationRules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'course' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
        ];

        if ($request->filled('password')) {
            $validationRules['password'] = 'required|min:6|confirmed';
        }

        $validated = $request->validate($validationRules);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $student->save();

        return redirect()->route('student.profile.show')->with('message', 'Profile updated successfully');
    }
}
