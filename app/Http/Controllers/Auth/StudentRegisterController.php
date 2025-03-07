<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentRegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.student-register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'student_id' => ['required', 'regex:/^\d{1,2}-?\d{1,5}$/', 'unique:students,student_id'],
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:students',
            'course' => 'required',
            'contact_number' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $studentId = preg_replace('/[^0-9]/', '', $request->student_id); 
        $formattedStudentId = substr($studentId, 0, 2) . '-' . substr($studentId, 2, 5); 

        Student::create([
            'student_id' => $formattedStudentId,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'course' => $request->course,
            'contact_number' => $request->contact_number,
            'password' => Hash::make($request->password),
        ]);


        return redirect('/login')->with('message', 'Registration Successful');
    }
}