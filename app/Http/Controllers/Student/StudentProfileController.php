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
        $student = Auth::guard('student')->user();
        if (!$student) {
            \Log::error('Student not found in auth guard');
            return redirect()->route('login');
        }
        return view('student.profile.show', compact('student'));
    } catch (\Exception $e) {
        \Log::error('Error in profile show: ' . $e->getMessage());
        return back()->with('error', 'An error occurred while loading your profile');
    }
}

    public function edit()
    {
        $student = Auth::guard('student')->user();
        return view('student.profile.edit', compact('student'));
    }

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

        // Update the student's attributes
        $student->fill($validated);

        if ($request->filled('password')) {
            $student->password = Hash::make($validated['password']);
        }

        $student->save();

        return redirect()->route('student.profile.show')
            ->with('message', 'Profile updated successfully');
    }
}
