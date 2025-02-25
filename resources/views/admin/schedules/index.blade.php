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
                    <input type="text" class="form-control" name="school_year" placeholder="e.g., 2023-2024" value="{{ request('school_year') }}">
                </div>
                <div class="col-md-3">
                    <label>Semester</label>
                    <select class="form-control" name="semester">
                        <option value="">All Semesters</option>
                        <option value="1st" {{ request('semester') == '1st' ? 'selected' : '' }}>1st Semester</option>
                        <option value="2nd" {{ request('semester') == '2nd' ? 'selected' : '' }}>2nd Semester</option>
                        <option value="summer" {{ request('semester') == 'summer' ? 'selected' : '' }}>Summer</option>
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
    <div class="card bg-white bg-opacity-30 backdrop-blur-sm shadow-lg rounded-lg">
        <div class="table-responsive">
            <table class="table">
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
                            <td>{{ optional($schedule->student)->first_name }} {{ optional($schedule->student)->last_name }}</td>
                            <td>{{ optional($schedule->file)->file_name ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($schedule->preferred_date)->format('M d, Y') }}</td>
                            <td>{{ $schedule->preferred_time }}</td>
                            <td>{{ $schedule->reason }}</td>
                            <td>{{ $schedule->school_year }}</td>
                            <td>{{ ucfirst($schedule->semester) }}</td>
                            <td class="status-{{ $schedule->status }}">{{ ucfirst($schedule->status) }}</td>
                            <td>
                                <form action="{{ route('schedules.approve', $schedule->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                </form>
                                <form action="{{ route('schedules.reject', $schedule->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                </form>
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
@endsection
