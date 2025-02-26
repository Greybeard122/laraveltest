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
        <p class="text-lg font-semibold text-gray-800">Student Number: 
            <span class="font-bold">{{ $student->student_id ?? 'N/A' }}</span>
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
            <div class="button-group">
                <button type="submit" class="filter-btn">
                    Filter
                </button>
                <a href="{{ route('admin.reports.student', $student->id) }}" class="reset-btn">
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
<style>
    /* Button Container */
    .button-group {
        display: flex;
        gap: 0.75rem; /* Adjust spacing between buttons */
        align-items: center;
    }

    /* Filter Button */
    .filter-btn {
        background-color: #0284c7; /* Tailwind's bg-sky-600 */
        color: white;
        font-weight: bold;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        transition: background-color 0.2s ease-in-out, transform 0.1s ease-in-out;
    }
    
    .filter-btn:hover {
        background-color: #0369a1; /* Tailwind's bg-sky-700 */
        transform: scale(1.05);
    }

    /* Reset Button */
    .reset-btn {
        background-color: #6b7280; /* Tailwind's bg-gray-500 */
        color: white;
        font-weight: bold;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        transition: background-color 0.2s ease-in-out, transform 0.1s ease-in-out;
    }

    .reset-btn:hover {
        background-color: #4b5563; /* Tailwind's bg-gray-600 */
        transform: scale(1.05);
    }
</style>
@endsection
