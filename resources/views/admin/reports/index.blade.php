@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 w-full">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-2xl font-semibold">Schedule Reports</h2>
        <a href="{{ route('admin.schedules.index') }}" class="archived-link">
            <i class="fas fa-calendar"></i> View Schedule Management
        </a>
    </div>

    <!-- Filter Form -->
    <div class="filter-box bg-white bg-opacity-30 backdrop-blur-sm shadow-lg rounded-lg p-4 mb-4">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-gray-700 font-bold mb-1">Search Student</label>
                <input type="text" name="search" class="form-control" placeholder="Enter student name" value="{{ request('search') }}">
            </div>
            
            <div>
                <label class="block text-gray-700 font-bold mb-1">File Type</label>
                <select name="file_id" class="form-control">
                    <option value="">All Files</option>
                    @foreach($files as $file)
                        <option value="{{ $file->id }}" {{ request('file_id') == $file->id ? 'selected' : '' }}>
                            {{ $file->file_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-1">School Year</label>
                <select name="school_year_id" class="form-control">
                    <option value="">All Years</option>
                    @foreach($schoolYears as $year)
                        <option value="{{ $year->id }}" {{ request('school_year_id') == $year->id ? 'selected' : '' }}>
                            {{ $year->year }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-1">Semester</label>
                <select name="semester_id" class="form-control">
                    <option value="">All Semesters</option>
                    @foreach($semesters as $semester)
                        <option value="{{ $semester->id }}" {{ request('semester_id') == $semester->id ? 'selected' : '' }}>
                            {{ $semester->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary mt-6">
                    <i class="fas fa-filter"></i> Apply Filters
                </button>
                <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary mt-6">
                    <i class="fas fa-undo"></i> Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Print Button -->
    <button onclick="printReport()" class="btn btn-success mb-4">
        <i class="fas fa-print"></i> Print Report
    </button>

    <!-- Reports Table -->
    <div class="schedule-table-container bg-white bg-opacity-30 backdrop-blur-sm shadow-lg rounded-lg p-4">
        <div class="schedule-table-responsive">
            <table class="schedule-table w-full" id="reportTable">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>File</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Reason</th>
                        <th>Term Requested</th>
                        <th>Copies</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedules as $schedule)
                        <tr>
                            <td>{{ $schedule->student->first_name ?? 'N/A' }} {{ $schedule->student->last_name ?? '' }}</td>
                            <td>
                                {{ optional($schedule->file)->file_name ?? 'N/A' }}
                                
                                @if(in_array(optional($schedule->file)->file_name, ['COR', 'COG']) && $schedule->manual_school_year && $schedule->manual_semester)
                                    <br>
                                    <small class="text-gray-500 text-sm">
                                        {{ $schedule->manual_school_year }} - {{ $schedule->manual_semester }}
                                    </small>
                                @endif
                            </td> 
                            <td>{{ \Carbon\Carbon::parse($schedule->preferred_date)->format('M d, Y') }}</td>
                            <td>{{ $schedule->preferred_time }}</td>
                            <td>{{ $schedule->reason }}</td>
                            <td>{{ optional($schedule->schoolYear)->year ?? 'N/A' }} - {{ $schedule->semester_name ?? 'N/A' }}</td> 
                            <td>{{ $schedule->copies }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($schedules->hasPages())
            <div class="pagination-container">
                {{ $schedules->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>
</div>

<!-- Print Function -->
<script>
    function printReport() {
        var printContents = document.getElementById('reportTable').outerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = "<h2>Schedule Report</h2>" + printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload();
    }
</script>
@endsection
