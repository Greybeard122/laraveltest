@extends('layouts.default')

@section('content')
<div class="flex justify-center items-center h-full">
    <div class="w-full max-w-3xl bg-white bg-opacity-30 backdrop-blur-sm shadow-lg rounded-lg p-8 sm:px-10">
        <h2 class="text-3xl font-bold text-gray-800 text-center mb-6">Edit Profile</h2>

        <form method="POST" action="{{ route('student.profile.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-gray-700 font-bold mb-1">First Name</label>
                <input type="text" name="first_name" class="w-full px-4 py-3 border rounded-lg" value="{{ old('first_name', $student->first_name) }}" required>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-1">Last Name</label>
                <input type="text" name="last_name" class="w-full px-4 py-3 border rounded-lg" value="{{ old('last_name', $student->last_name) }}" required>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-1">Email</label>
                <input type="email" name="email" class="w-full px-4 py-3 border rounded-lg" value="{{ old('email', $student->email) }}" required>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-1">Course</label>
                <input type="text" name="course" class="w-full px-4 py-3 border rounded-lg" value="{{ old('course', $student->course) }}" required>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-1">Contact Number</label>
                <input type="text" name="contact_number" class="w-full px-4 py-3 border rounded-lg" value="{{ old('contact_number', $student->contact_number) }}" required>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-1">New Password (Optional)</label>
                <input type="password" name="password" class="w-full px-4 py-3 border rounded-lg">
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" class="w-full px-4 py-3 border rounded-lg">
            </div>

            <button type="submit" class="w-full bg-purple-600 text-white font-bold py-3 rounded-lg">
                Update Profile
            </button>
        </form>
    </div>
</div>
@endsection
