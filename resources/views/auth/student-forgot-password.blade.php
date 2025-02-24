@extends('layouts.default')

@section('content')
<div class="flex justify-center items-center h-full">
    <div class="w-full max-w-3xl bg-white bg-opacity-50 backdrop-blur-sm shadow-lg rounded-lg p-8 sm:px-10">
        <div class="text-center mb-6">
            <h2 class="text-3xl font-bold text-gray-800 drop-shadow-lg">Forgot Password</h2>
            <p class="text-gray-600 mt-2">Enter your email to receive a password reset link.</p>
        </div>

        @if (session('message'))
            <div class="bg-green-100 text-green-600 px-4 py-2 rounded mb-4 text-center">
                {{ session('message') }}
            </div>
        @endif

        <form method="POST" action="{{ route('student.password.email') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-gray-700 font-bold mb-1">Email Address</label>
                <input type="email" name="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 transition duration-200" required>
            </div>

            <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 rounded-lg shadow-md transition duration-200">
                Send Reset Link
            </button>

            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="text-purple-600 font-semibold text-lg hover:underline hover:text-purple-800 transition duration-200">
                    Back to Login
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
