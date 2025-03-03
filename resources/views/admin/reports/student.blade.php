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
                <select name="school_year_id" id="school_year_select" class="w-full border-2 border-gray-300 rounded-lg p-2">
                    <option value="">All School Years</option>
                    @foreach($schoolYears as $year)
                        <option value="{{ $year->id }}" {{ request('school_year_id') == $year->id ? 'selected' : '' }}>
                            {{ $year->year }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Semester Filter (Dependent on School Year) -->
            <div>
                <label class="block text-gray-700 font-bold mb-1">Semester</label>
                <select name="semester_id" id="semester_select" class="w-full border-2 border-gray-300 rounded-lg p-2">
                    <option value="">All Semesters</option>
                    @foreach($semesters as $semester)
                        <option value="{{ $semester->id }}" data-year="{{ $semester->school_year_id }}" 
                            {{ request('semester_id') == $semester->id ? 'selected' : '' }}>
                            {{ str_replace('Semester', '', $semester->name) }}
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
                        <th>School Year</th>
                        <th>Semester</th>
                        <th>Status</th>
                        <th>Date Requested</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($studentSchedules as $schedule)
                        <tr>
                            <td>{{ optional($schedule->file)->file_name ?? 'N/A' }}</td>
                            <td class="text-center font-bold">{{ $schedule->request_count }}</td>
                            <td>{{ optional($schedule->schoolYear)->year ?? 'N/A' }}</td>
                            <td>{{ str_replace('Semester', '', optional($schedule->semester)->name ?? 'N/A') }}</td>
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
        gap: 0.75rem; 
        align-items: center;
    }

    /* Filter Button */
    .filter-btn {
        background-color: #0284c7; 
        color: white;
        font-weight: bold;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        transition: background-color 0.2s ease-in-out, transform 0.1s ease-in-out;
    }
    
    .filter-btn:hover {
        background-color: #0369a1; 
    }

    /* Reset Button */
    .reset-btn {
        background-color: #6b7280;
        color: white;
        font-weight: bold;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        transition: background-color 0.2s ease-in-out, transform 0.1s ease-in-out;
    }

    .reset-btn:hover {
        background-color: #4b5563; 
        transform: scale(1.05);
    }

    /* Status Badges */
    .badge {
        padding: 0.35em 0.65em;
        font-size: 0.75em;
        font-weight: 600;
        border-radius: 0.25rem;
        text-transform: uppercase;
    }

    .bg-success { background-color: #10b981; color: white; }
    .bg-danger { background-color: #ef4444; color: white; }
    .bg-warning { background-color: #f59e0b; color: white; }

    /* Table Column Spacing */
    .table th, .table td {
        padding: 12px 16px; /* Increased padding for spacing */
    }

    /* Table Row Spacing */
    .table tr {
        border-bottom: 1px solid var(--border-color);
    }
</style>

<!-- JavaScript for Dynamic Semester Filtering -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const schoolYearSelect = document.getElementById("school_year_select");
        const semesterSelect = document.getElementById("semester_select");

        function updateSemesters() {
            const selectedYear = schoolYearSelect.value;
            const options = semesterSelect.querySelectorAll("option");

            options.forEach(option => {
                if (option.value === "") return; // Keep "All Semesters" option visible

                if (!selectedYear || option.getAttribute("data-year") === selectedYear) {
                    option.style.display = "block";
                } else {
                    option.style.display = "none";
                }
            });
        }

        schoolYearSelect.addEventListener("change", updateSemesters);
        updateSemesters(); // Initialize on page load
    });
</script>
@endsection
