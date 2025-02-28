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
    public function showLoginForm(Request $request)
{
    // Force logout before showing the login page
    if (Auth::guard('web')->check()) {
        Auth::guard('web')->logout();
    }

    if (Auth::guard('student')->check()) {
        Auth::guard('student')->logout();
    }

    // Invalidate session and regenerate CSRF token
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return view('auth.login');
}


public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $remember = $request->has('remember'); // Check if "Remember Me" is checked

    // Try admin login first
    if (Auth::guard('web')->attempt($credentials, $remember)) {
        $user = Auth::guard('web')->user();
        return redirect()->intended('/admin/dashboard')->with('message', 'Welcome, ' . $user->name . '!');
    }

    // Try student login
    if (Auth::guard('student')->attempt($credentials, $remember)) {
        $student = Auth::guard('student')->user();
        return redirect()->intended('/student/dashboard')->with('message', 'Welcome, ' . $student->first_name . '!');
    }

    return back()->withErrors([
        'email' => 'Invalid credentials',
    ])->with('message', 'Please check your credentials and try again.');
}



public function logout(Request $request)
{
    if (Auth::guard('web')->check()) {
        Auth::guard('web')->logout();
    } else {
        Auth::guard('student')->logout();
    }

    // Invalidate session and regenerate CSRF token
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login')->with('message', 'Logout Successful');
}

}