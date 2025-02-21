@extends('layouts.default')

@section('main-class', 'min-h-[30vh]') <!-- Set custom height for the admin registration page -->

@section('content')
<div class="flex justify-center items-center h-full">
    <div class="w-full max-w-3xl bg-white bg-opacity-30 backdrop-blur-sm shadow-lg rounded-lg p-8 sm:px-10">
        <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center drop-shadow-lg">Admin Registration</h2>
        
        <form method="POST" action="{{ route('admin.register') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-gray-700 font-bold mb-1">Name</label>
                <input type="text" name="name" class="w-full border-2 border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-sky-500 focus:border-sky-500" required>
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-1">Faculty ID</label>
                <input type="text" name="faculty_id" class="w-full border-2 border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-sky-500 focus:border-sky-500" required>
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-1">Email</label>
                <input type="email" name="email" class="w-full border-2 border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-sky-500 focus:border-sky-500" required>
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-1">Password</label>
                <input type="password" name="password" class="w-full border-2 border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-sky-500 focus:border-sky-500" required>
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" class="w-full border-2 border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-sky-500 focus:border-sky-500" required>
            </div>
            <button type="submit" class="w-full bg-sky-600 text-white font-bold py-3 rounded-lg hover:bg-sky-700 transition duration-200">
                Register Admin
            </button>
        </form>
    </div>
</div>
@endsection
