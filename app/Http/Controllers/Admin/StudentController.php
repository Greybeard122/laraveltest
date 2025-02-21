<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;


    class StudentController extends Controller
{
    // Display the list of students
    public function index()
{
    $query = Student::query();
    
    // Handle sorting
    if (request()->has('sort')) {
        $sortColumn = request('sort');
        $direction = request('direction', 'asc');
        
        // Validate sort column to prevent SQL injection
        $allowedColumns = [
            'student_id', 'first_name', 'last_name', 
            'email', 'course', 'contact_number'
        ];
        
        if (in_array($sortColumn, $allowedColumns)) {
            $query->orderBy($sortColumn, $direction);
        }
    } else {
        // Default sorting
        $query->orderBy('student_id', 'asc');
    }
    
    $students = $query->paginate(10);
    return view('admin.students.index', compact('students'));
}

    // Show the form to create a new student
    public function create()
    {
        return view('admin.students.create');
    }

    // Store a new student in the database
    public function store(Request $request)
{
    // Validate the input with password confirmation
    $validated = $request->validate([
        'student_id' => 'required|unique:students,student_id', // Ensure student_id is unique
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required|email|unique:students,email',
        'course' => 'required',
        'contact_number' => 'required',
        'password' => 'required|min:6|confirmed', // Password confirmation validation rule
    ]);

    // Create the student with the hashed password
    $student = Student::create([
        'student_id' => $validated['student_id'],
        'first_name' => $validated['first_name'],
        'last_name' => $validated['last_name'],
        'email' => $validated['email'],
        'course' => $validated['course'],
        'contact_number' => $validated['contact_number'],
        'password' => bcrypt($validated['password']), // Hash the password
    ]);

    return redirect()->route('admin.students.index')->with('success', 'Student added successfully');
}

    


    // Show the form to edit a student
    public function edit(Student $student)
    {
        return view('admin.students.edit', compact('student'));
    }

    // Update an existing student's details
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:students,email,'.$student->id,
            'course' => 'required',
            'contact_number' => 'required'
        ]);

        $student->update($validated);

        return redirect()->route('admin.students.index')->with('success', 'Student updated successfully');
    }

    
    // Delete a student
    public function destroy($id)
{
    try {
        $student = Student::findOrFail($id);
        // The related schedules will be automatically deleted due to onDelete('cascade')
        $student->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Student deleted successfully'
        ]);
    } catch (\Exception $e) {
        \Log::error('Error deleting student: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error deleting student: ' . $e->getMessage()
        ], 500);
    }
}

}
