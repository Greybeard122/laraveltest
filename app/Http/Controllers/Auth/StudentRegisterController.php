<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StudentRegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.student-register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => ['required', 'regex:/^\d{2}-\d{5}$/', 'unique:students,student_id'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:students,email'],
            'course' => ['required', 'string'],
            'contact_number' => ['required', 'regex:/^\d{11}$/'],
            'password' => ['required', 'min:6', 'confirmed'],
        ], [
            'student_id.regex' => 'Student ID must be in the format XX-XXXXX.',
            'contact_number.regex' => 'Contact number must be 11 digits long.',
            'password.confirmed' => 'Passwords do not match.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Student::create([
            'student_id' => $request->student_id, // Already formatted by JavaScript
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'course' => $request->course,
            'contact_number' => $request->contact_number,
            'password' => Hash::make($request->password),
        ]);

        return redirect('/login')->with('message', 'Registration Successful! Please log in.');
    }
}
