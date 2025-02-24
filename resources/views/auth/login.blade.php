@extends('layouts.default')

@section('content')
<div class="flex justify-center items-center h-full">
    <div class="w-full max-w-3xl bg-white bg-opacity-50 backdrop-blur-sm shadow-lg rounded-lg p-8 sm:px-10">
        <div class="text-center mb-6">
            <h2 class="text-3xl font-bold text-gray-800 drop-shadow-lg">Login</h2>
        </div>

        @if(session('error'))
            <div class="bg-red-100 text-red-600 px-4 py-2 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="bg-green-100 text-green-600 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-gray-700 font-bold mb-1">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 transition duration-200" required autofocus>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-gray-700 font-bold mb-1">Password</label>
                <input type="password" id="password" name="password" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 transition duration-200" required>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-between items-center text-sm">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="mr-2">
                    Remember Me
                </label>

                <!-- Forgot Password Link -->
                <a href="{{ route('student.password.request') }}" class="text-purple-600 hover:underline hover:text-purple-800 transition duration-200">
                    Forgot Password?
                </a>
            </div>

            <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 rounded-lg shadow-md transition duration-200">
                Login
            </button>

            <div class="text-center mt-3">
                <a href="{{ route('register') }}" class="text-purple-600 font-semibold text-lg hover:underline hover:text-purple-800 transition duration-200">
                    Register
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
