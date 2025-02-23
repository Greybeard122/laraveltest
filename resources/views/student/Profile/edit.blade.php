@extends('layouts.default')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg p-6 mt-10">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Edit Profile</h2>

    @if (session('message'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <form method="POST" action="{{ route('student.profile.update') }}">
        @csrf
        @method('PUT')

        <!-- Student ID -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Student ID</label>
            <input type="text" name="student_id" value="{{ old('student_id', $student->student_id) }}" 
                   class="w-full p-2 border rounded-lg" readonly>
        </div>

        <!-- First Name -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">First Name</label>
            <input type="text" name="first_name" value="{{ old('first_name', $student->first_name) }}" 
                   class="w-full p-2 border rounded-lg" required>
        </div>

        <!-- Last Name -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Last Name</label>
            <input type="text" name="last_name" value="{{ old('last_name', $student->last_name) }}" 
                   class="w-full p-2 border rounded-lg" required>
        </div>

        <!-- Email -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Email</label>
            <input type="email" name="email" value="{{ old('email', $student->email) }}" 
                   class="w-full p-2 border rounded-lg" required>
        </div>

        <!-- Course -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Course</label>
            <input type="text" name="course" value="{{ old('course', $student->course) }}" 
                   class="w-full p-2 border rounded-lg" required>
        </div>

        <!-- Contact Number -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Contact Number</label>
            <input type="text" name="contact_number" value="{{ old('contact_number', $student->contact_number) }}" 
                   class="w-full p-2 border rounded-lg" required>
        </div>

        <!-- Password (Optional) -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">New Password (optional)</label>
            <input type="password" name="password" class="w-full p-2 border rounded-lg">
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Confirm Password</label>
            <input type="password" name="password_confirmation" class="w-full p-2 border rounded-lg">
        </div>

        <!-- Submit Button -->
        <div class="text-right">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Update Profile
            </button>
        </div>
    </form>
</div>
@endsection
