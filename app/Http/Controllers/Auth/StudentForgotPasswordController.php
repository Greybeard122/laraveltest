<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class StudentForgotPasswordController extends Controller
{
    /**
     * Show the forgot password request form.
     */
    public function showForgotPasswordForm()
    {
        return view('auth.student-forgot-password');
    }

    /**
     * Handle sending the reset password email.
     */
    public function sendResetLink(Request $request)
{
    
    Log::info('Password Reset Request Initiated', ['email' => $request->email]);

    $request->validate(['email' => 'required|email|exists:students,email']);

    dd($request->all());
    
    Log::info('Validation Passed', ['email' => $request->email]);

    $status = Password::broker('students')->sendResetLink(
        $request->only('email')
    );

    Log::info('Password Reset Link Sent Status', ['status' => $status]);

    return $status === Password::RESET_LINK_SENT
        ? back()->with('message', 'Password reset link sent to your email!')
        : back()->withErrors(['email' => 'Failed to send reset link.']);
}

    /**
     * Show the password reset form.
     */
    public function showResetForm($token)
    {
        return view('auth.student-reset-password', ['token' => $token]);
    }

    /**
     * Handle resetting the student's password.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:students,email',
            'password' => 'required|min:6|confirmed',
            'token' => 'required'
        ]);

        $status = Password::broker('students')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($student, $password) {
                $student->password = Hash::make($password);
                $student->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('message', 'Password reset successfully!')
            : back()->withErrors(['email' => 'Failed to reset password.']);
    }
}
