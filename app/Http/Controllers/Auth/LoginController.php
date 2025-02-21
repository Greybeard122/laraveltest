<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Student;

class LoginController extends Controller
{
    public function showLogin()
{
    $title = 'Login page';
    return view('auth.login', compact('title'));
}
    public function index()
    {
        return view('auth.login');
    }
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    // Try admin login first
    if (Auth::guard('web')->attempt($credentials)) {
        // Access the admin's first name
        $user = Auth::guard('web')->user();
        $firstName = $user->name; // Assuming 'name' is the column for the first name in users table

        return redirect()->intended('/admin/dashboard')->with('message', 'Welcome, ' . $user->name . '!');
    }

    // Try student login
    if (Auth::guard('student')->attempt($credentials)) {
        // Access the student's first name
        $student = Auth::guard('student')->user();
        $firstName = $student->first_name; // Assuming 'first_name' is the column for the first name in students table

        return redirect()->intended('/student/dashboard')->with('message', 'Welcome, ' . $student->first_name . '!');
    }

    return back()->withErrors([
        'email' => 'Invalid credentials',
    ])->with('message', 'Please check your credentials and try again.');
}


    public function logout()
    {
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        } else {
            Auth::guard('student')->logout();
        }
        return redirect('/login')->with('message', 'Logout Successful');
        
    }
}