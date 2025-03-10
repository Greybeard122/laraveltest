@extends('layouts.default')

@section('main-class', 'min-h-[30vh]') <!-- Set custom height for the registration page -->

@section('content')
<div class="flex justify-center items-center h-full">
    <!-- Form Container with Blur Effect -->
    <div class="w-full max-w-3xl bg-white bg-opacity-50 backdrop-blur-sm shadow-lg rounded-lg p-8 sm:px-10">
        <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center drop-shadow-lg">
            Student Registration
        </h2>

        <!-- Success Message -->
        @if(session('message'))
            <p class="text-green-600 text-sm font-bold text-center mb-4">
                {{ session('message') }}
            </p>
        @endif

        <!-- Registration Form -->
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Student ID -->
            <div>
                <label class="block text-gray-700 font-bold mb-1">Student ID</label>
                <input type="text" name="student_id" value="{{ old('student_id') }}" 
                       class="w-full border-2 border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-sky-500 focus:border-sky-500" 
                       required maxlength="8" placeholder="XX-XXXXX">
                @error('student_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- First Name -->
            <div>
                <label class="block text-gray-700 font-bold mb-1">First Name</label>
                <input type="text" name="first_name" value="{{ old('first_name') }}" 
                       class="w-full border-2 border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-sky-500 focus:border-sky-500" 
                       required>
                @error('first_name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Last Name -->
            <div>
                <label class="block text-gray-700 font-bold mb-1">Last Name</label>
                <input type="text" name="last_name" value="{{ old('last_name') }}" 
                       class="w-full border-2 border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-sky-500 focus:border-sky-500" 
                       required>
                @error('last_name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label class="block text-gray-700 font-bold mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" 
                       class="w-full border-2 border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-sky-500 focus:border-sky-500" 
                       required>
                @error('email')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Course -->
            <div>
                <label class="block text-gray-700 font-bold mb-1">Course</label>
                <input type="text" name="course" value="{{ old('course') }}" 
                       class="w-full border-2 border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-sky-500 focus:border-sky-500" 
                       required>
                @error('course')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Contact Number -->
            <div>
                <label class="block text-gray-700 font-bold mb-1">Contact Number</label>
                <input type="text" name="contact_number" value="{{ old('contact_number') }}" 
                       class="w-full border-2 border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-sky-500 focus:border-sky-500" 
                       required maxlength="11">
                @error('contact_number')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label class="block text-gray-700 font-bold mb-1">Password</label>
                <input type="password" name="password" 
                       class="w-full border-2 border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-sky-500 focus:border-sky-500" 
                       required>
                @error('password')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block text-gray-700 font-bold mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" 
                       class="w-full border-2 border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-sky-500 focus:border-sky-500" 
                       required>
                @error('password_confirmation')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-sky-600 text-white font-bold py-3 rounded-lg hover:bg-sky-700 transition duration-200">
                Register
            </button>
        </form>
    </div>
</div>

<!-- JavaScript to format Student ID -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const studentIdInput = document.querySelector('input[name="student_id"]');

        studentIdInput.addEventListener("input", function () {
            let value = this.value.replace(/\D/g, ""); // Remove non-numeric characters
            if (value.length >= 3) {
                this.value = value.slice(0, 2) + "-" + value.slice(2, 7); // Format as XX-XXXXX
            }
        });
    });
</script>

@endsection
