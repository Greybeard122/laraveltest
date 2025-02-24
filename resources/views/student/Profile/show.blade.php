@extends('layouts.default')

@section('content')
<div class="flex justify-center items-center h-full">
    <div class="w-full max-w-3xl bg-white bg-opacity-30 backdrop-blur-sm shadow-lg rounded-lg p-8 sm:px-10">
        <h2 class="text-3xl font-bold text-gray-800 text-center mb-6">My Profile</h2>

        @if (session('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('message') }}
            </div>
        @endif

        <div class="space-y-4">
            <div>
                <label class="block text-gray-700 font-bold mb-1">Student ID</label>
                <p class="px-4 py-3 bg-gray-50 rounded-lg">{{ $student->student_id }}</p>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-1">Name</label>
                <p class="px-4 py-3 bg-gray-50 rounded-lg">{{ $student->first_name }} {{ $student->last_name }}</p>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-1">Email</label>
                <p class="px-4 py-3 bg-gray-50 rounded-lg">{{ $student->email }}</p>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-1">Course</label>
                <p class="px-4 py-3 bg-gray-50 rounded-lg">{{ $student->course }}</p>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-1">Contact Number</label>
                <p class="px-4 py-3 bg-gray-50 rounded-lg">{{ $student->contact_number }}</p>
            </div>

            <div class="mt-6">
                <a href="{{ route('student.profile.edit') }}" 
                   class="block w-full text-center bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 rounded-lg shadow-md transition duration-200">
                    Edit Profile
                </a>
            </div>
        </div>
    </div>
</div>
@endsection