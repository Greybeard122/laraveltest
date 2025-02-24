@extends('layouts.default')

@section('content')
<div class="flex justify-center items-center h-full">
    <div class="w-full max-w-3xl bg-white bg-opacity-50 backdrop-blur-sm shadow-lg rounded-lg p-8 sm:px-10">
        <div class="text-center mb-6">
            <h2 class="text-3xl font-bold text-gray-800 drop-shadow-lg">Reset Password</h2>
            <p class="text-gray-600 mt-2">Enter a new password for your account.</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 text-red-600 px-4 py-2 rounded mb-4">
                <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
            </div>
        @endif

        <form method="POST" action="{{ route('student.password.update') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label class="block text-gray-700 font-bold mb-1">Email Address</label>
                <input type="email" name="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 transition duration-200" required>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-1">New Password</label>
                <input type="password" name="password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 transition duration-200" required>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 transition duration-200" required>
            </div>

            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg shadow-md transition duration-200">
                Reset Password
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
