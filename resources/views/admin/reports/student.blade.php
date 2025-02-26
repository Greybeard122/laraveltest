@extends('layouts.default')

@section('content')
<div class="container mx-auto px-4">
    <div class="text-center mb-6">
        <h2 class="text-3xl font-bold text-black drop-shadow-md">
            Report for {{ $student->last_name }}, {{ $student->first_name }}
        </h2>
    </div>

    <!-- Student Information -->
    <div class="card bg-white bg-opacity-50 backdrop-blur-sm shadow-lg rounded-lg p-6 mb-6">
        <p class="text-lg font-semibold text-gray-800">
            <span class="text-gray-600">Student Number:</span> 
            <span class="font-bold">{{ $student->student_number ?? 'N/A' }}</span>
        </p>
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
                <thead class="bg-gray-200">
                    <tr class="text-gray-700">
                        <th class="px-4 py-2">File Name</th>
                        <th class="px-4 py-2">Request Count</th>
                        <th class="px-4 py-2">Semester</th>
                        <th class="px-4 py-2">School Year</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Date Requested</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    @foreach($studentSchedules as $schedule)
                        <tr class="text-center text-gray-700">
                            <td class="px-4 py-2">{{ optional($schedule->file)->file_name ?? 'N/A' }}</td>
                            <td class="px-4 py-2 font-bold">{{ $schedule->request_count }}</td>
                            <td class="px-4 py-2">{{ optional($schedule->semester)->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ optional($schedule->schoolYear)->year ?? 'N/A' }}</td>
                            <td class="px-4 py-2 capitalize">{{ ucfirst($schedule->status) }}</td>
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($schedule->latest_request)->format('M d, Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $studentSchedules->links() }}
    </div>
</div>
@endsection
