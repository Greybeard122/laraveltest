@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 w-full">
    <!-- Title and Archived Link -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-2xl font-semibold">Schedule Management</h2>
        <a href="{{ route('admin.reports.index') }}" class="archived-link">
            <i class="fas fa-archive"></i> View Report Page
        </a>
    </div>

    <!-- Filter Form -->
    <div class="card mb-4 bg-white bg-opacity-30 backdrop-blur-sm shadow-lg rounded-lg filter-box">
        <div class="card-body">
            <form class="row g-3" method="GET" action="{{ route('admin.schedules.index') }}">
                <div class="col-md-3">
                    <label>File Type</label>
                    <select class="form-control" name="file_id">
                        <option value="">All Files</option>
                        @foreach($files as $file)
                            <option value="{{ $file->id }}" {{ request('file_id') == $file->id ? 'selected' : '' }}>
                                {{ $file->file_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Status</label>
                    <select class="form-control" name="status">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>School Year</label>
                    <select class="form-control" name="school_year_id">
                        <option value="">All School Years</option>
                        @foreach($schoolYears as $year)
                            <option value="{{ $year->id }}" {{ request('school_year_id') == $year->id ? 'selected' : '' }}>
                                {{ $year->year }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Semester</label>
                    <select class="form-control" name="semester_id">
                        <option value="">All Semesters</option>
                        @foreach($semesters as $semester)
                            <option value="{{ $semester->id }}" {{ request('semester_id') == $semester->id ? 'selected' : '' }}>
                                {{ $semester->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary ms-2">
                        <i class="fas fa-undo"></i> Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="alert alert-info mb-4">
        <i class="fas fa-info-circle me-2"></i> All schedule requests are kept on this page for 7 days to allow status changes. Older requests are saved on the Report Page.
    </div>

    @if(session('success'))
        <div class="alert alert-success fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    <!-- Schedules Table -->
    <div class="table-container">
        <div class="table-responsive">
            <table class="table schedule-table">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>File</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Reason</th>
                        <th>School Year</th>
                        <th>Semester</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedules as $schedule)
                        <tr>
                            <td class="stack-text student-name">
                                <span>{{ $schedule->student->first_name ?? 'N/A' }}</span>
                                <span>{{ $schedule->student->last_name ?? '' }}</span>
                            </td>
                            <td>{{ optional($schedule->file)->file_name ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($schedule->preferred_date)->format('M d, Y') }}</td>
                            <td>{{ $schedule->preferred_time }}</td>
                            <td class="stack-text prevent-break">{{ $schedule->reason }}</td>
                            <td class="stack-text school-year">
                                <span>{{ optional($schedule->schoolYear)->year ? explode('-', optional($schedule->schoolYear)->year)[0] : 'N/A' }}</span>
                                <span>{{ optional($schedule->schoolYear)->year ? explode('-', optional($schedule->schoolYear)->year)[1] : '' }}</span>
                            </td>
                            <td class="stack-text">{{ optional($schedule->semester)->name ?? 'N/A' }}</td>
                            <td class="status-{{ $schedule->status }}">{{ ucfirst($schedule->status) }}</td>
                            <td>
                                <div class="button-container">
                                    <form action="{{ route('schedules.approve', $schedule->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="fas fa-check me-1"></i> Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('schedules.reject', $schedule->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-times me-1"></i> Reject
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
/* Ensure table takes full width */
.schedule-table {
    width: 100%;
    border-collapse: collapse;
    table-layout: auto;
}

/* Apply custom styling for stacked text */
.stack-text {
    display: flex;
    flex-direction: column;
    align-items: center;
    word-break: keep-all;
}

/* Student Name Stacking */
.student-name {
    font-weight: bold;
}

/* Semester & School Year Styling */
.school-year {
    display: flex;
    flex-direction: column;
}

/* Prevent single letters from breaking */
.prevent-break {
    word-break: keep-all;
    overflow-wrap: break-word;
}

/* Remove horizontal scroll */
.table-container {
    overflow-x: auto;
    white-space: nowrap;
}

/* Optimize for smaller screens */
@media screen and (max-width: 768px) {
    .schedule-table th, .schedule-table td {
        font-size: 14px;
        padding: 8px;
    }
}
</style>
@endsection
