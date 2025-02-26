@extends('layouts.default')
<link href="{{ asset('css/students2.css') }}" rel="stylesheet">

@section('content')
<div class="container mx-auto px-4">
    <div class="text-center mb-6">
        <h2 class="text-3xl font-bold text-black drop-shadow-md">
            Report for {{ $student->last_name }}, {{ $student->first_name }}
        </h2>
    </div>

    <!-- Student Information -->
    <div class="card bg-white bg-opacity-50 backdrop-blur-sm shadow-lg rounded-lg p-6 mb-6">
        <p class="text-lg font-semibold text-gray-800">Student Name: 
            <span class="font">{{ $student->last_name }}, {{ $student->first_name }}</span>
        </p>
        <p class="text-lg font-semibold text-gray-800">Student Number: <span class="font-bold">{{ $student->student_number }}</span></p>
    </div>

    <!-- Filters -->
    <div class="card bg-white bg-opacity-50 backdrop-blur-sm shadow-lg rounded-lg p-6 mb-6">
        <form method="GET" action="{{ route('admin.reports.student', $student->id) }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- School Year Filter -->
            <div>
                <label class="block text-gray-700 font-bold mb-1">School Year</label>
                <select name="school_year_id" class="w-full border-2 border-gray-300 rounded-lg p-2">
                    <option value="">All School Years</option>
                    @foreach($schoolYears as $year)
                        <option value="{{ $year->id }}" {{ request('school_year_id') == $year->id ? 'selected' : '' }}>
                            {{ $year->year }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Semester Filter -->
            <div>
                <label class="block text-gray-700 font-bold mb-1">Semester</label>
                <select name="semester_id" class="w-full border-2 border-gray-300 rounded-lg p-2">
                    <option value="">All Semesters</option>
                    @foreach($semesters as $semester)
                        <option value="{{ $semester->id }}" {{ request('semester_id') == $semester->id ? 'selected' : '' }}>
                            {{ $semester->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Submit & Reset -->
            <div class="flex items-end">
                <button type="submit" class="bg-sky-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-sky-700 transition duration-200">
                    Filter
                </button>
                <a href="{{ route('admin.reports.student', $student->id) }}" class="ml-4 bg-gray-400 text-white font-bold py-2 px-4 rounded-lg hover:bg-gray-500 transition duration-200">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Report Table -->
    <div class="card bg-white bg-opacity-50 backdrop-blur-sm shadow-lg rounded-lg p-6">
        <div class="table-responsive">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th>File Name</th>
                        <th>Request Count</th>
                        <th>Semester</th>
                        <th>School Year</th>
                        <th>Status</th>
                        <th>Date Requested</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($studentSchedules as $schedule)
                        <tr>
                            <td>{{ optional($schedule->file)->file_name ?? 'N/A' }}</td>
                            <td class="text-center font-bold">{{ $schedule->request_count }}</td>
                            <td>{{ optional($schedule->semester)->name ?? 'N/A' }}</td>
                            <td>{{ optional($schedule->schoolYear)->year ?? 'N/A' }}</td>
                            <td class="capitalize">{{ ucfirst($schedule->status) }}</td>
                            <td>{{ \Carbon\Carbon::parse($schedule->latest_request)->format('M d, Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $studentSchedules->links() }}
    </div>
</div>
@endsection
