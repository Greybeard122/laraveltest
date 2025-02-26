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
            <table class="table">
                <thead>
                    <tr>
                        <th><a href="{{ route('admin.schedules.index', array_merge(request()->all(), ['sort' => 'student_id', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}">Student ⬍</a></th>
                        <th><a href="{{ route('admin.schedules.index', array_merge(request()->all(), ['sort' => 'file_id', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}">File ⬍</a></th>
                        <th><a href="{{ route('admin.schedules.index', array_merge(request()->all(), ['sort' => 'preferred_date', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}">Date ⬍</a></th>
                        <th><a href="{{ route('admin.schedules.index', array_merge(request()->all(), ['sort' => 'preferred_time', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}">Time ⬍</a></th>
                        <th><a href="{{ route('admin.schedules.index', array_merge(request()->all(), ['sort' => 'reason', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}">Reason ⬍</a></th>
                        <th><a href="{{ route('admin.schedules.index', array_merge(request()->all(), ['sort' => 'school_year_id', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}">School Year ⬍</a></th>
                        <th><a href="{{ route('admin.schedules.index', array_merge(request()->all(), ['sort' => 'semester_id', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}">Semester ⬍</a></th>
                        <th><a href="{{ route('admin.schedules.index', array_merge(request()->all(), ['sort' => 'status', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}">Status ⬍</a></th>
                        <th>Actions</th>
                    </tr>
                </thead>
                
                <tbody>
                    @foreach($schedules as $schedule)
                        <tr>
                            <td>
                                @if($schedule->student)
                                    <a href="{{ route('admin.reports.student', $schedule->student->id) }}" 
                                        class="text-blue-600 hover:text-blue-800 hover:underline">
                                        {{ $schedule->student->first_name }} {{ $schedule->student->last_name }}
                                    </a>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ optional($schedule->file)->file_name ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($schedule->preferred_date)->format('M d, Y') }}</td>
                            <td>{{ $schedule->preferred_time }}</td>
                            <td>{{ $schedule->reason }}</td>
                            <td>{{ optional($schedule->schoolYear)->year ?? 'N/A' }}</td>
                            <td>{{ optional($schedule->semester)->name ?? 'N/A' }}</td>
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

        @if($schedules->hasPages())
            <div class="p-4 flex justify-center">
                {{ $schedules->links('vendor.pagination.tailwind') }}
            </div>
        @endif
    </div>
</div>
<style>
/* Improve Table Layout */
.table-container {
    width: 100%;
    overflow-x: auto;
}

.table {
    width: 100%;
    table-layout: auto; /* Allows flexible column widths */
    border-collapse: collapse;
}

.table th, .table td {
    padding: 12px;
    white-space: nowrap; /* Prevents text wrapping */
    text-align: center;
}

/* Ensure specific columns expand based on content */
.table td:nth-child(2), /* File Name */
.table td:nth-child(5)  /* Reason */ {
    min-width: 200px; /* Allow long content to expand */
    white-space: normal;
    word-wrap: break-word;
}

/* Status Column Styling */
.table td.status-pending {
    background-color: rgba(255, 193, 7, 0.2);
    color: #e67e22;
}

.table td.status-approved {
    background-color: rgba(40, 167, 69, 0.2);
    color: #27ae60;
}

.table td.status-rejected {
    background-color: rgba(220, 53, 69, 0.2);
    color: #e74c3c;
}

/* Responsive Styles */
@media screen and (max-width: 768px) {
    .table th, .table td {
        padding: 8px;
        font-size: 14px;
    }
    
    .table td:nth-child(2),
    .table td:nth-child(5) {
        min-width: 150px; /* Adjust for smaller screens */
    }
}

</style>
@endsection
