@extends('layouts.admin') 

@section('content')
<div class="container mx-auto px-4 w-full">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-2xl font-semibold">Schedule Reports</h2>
        <a href="{{ route('admin.schedules.index') }}" class="archived-link">
            <i class="fas fa-calendar"></i> View Schedule Management
        </a>
    </div>

    <!-- 🔍 Search & Filter Form -->
    <div class="card mb-4 bg-white bg-opacity-30 backdrop-blur-sm shadow-lg rounded-lg filter-box">
        <div class="card-body">
            <form class="row g-3" method="GET" action="{{ route('admin.reports.index') }}">
                <div class="col-md-3">
                    <label>Search</label>
                    <input type="text" class="form-control" name="search" placeholder="Search by student or file" value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label>From</label>
                    <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label>To</label>
                    <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                </div>
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
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Manual School Year (For COR/COG)</label>
                    <input type="text" class="form-control" name="manual_school_year" placeholder="Enter manually..." value="{{ request('manual_school_year') }}">
                </div>
                <div class="col-md-3">
                    <label>Manual Semester (For COR/COG)</label>
                    <input type="text" class="form-control" name="manual_semester" placeholder="Enter manually..." value="{{ request('manual_semester') }}">
                </div>
                <div class="col-md-3">
                    <label>Number of Copies</label>
                    <input type="number" class="form-control" name="copies" min="1" value="{{ request('copies') }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary ms-2">
                        <i class="fas fa-undo"></i> Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- 📂 Reports Table -->
    <div class="schedule-table-container">
        <div class="schedule-table-responsive">
            <table class="schedule-table">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>File</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Reason</th>
                        <th>School Year</th>
                        <th>Semester</th>
                        <th>Manual SY (COR/COG)</th>
                        <th>Manual Sem (COR/COG)</th>
                        <th>Copies</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedules as $schedule)
                        <tr>
                            <td>
                                @if($schedule->student)
                                    <a href="{{ route('admin.reports.student', $schedule->student->id) }}" class="student-link">
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
                            <td>{{ in_array(optional($schedule->file)->file_name, ['COR', 'COG']) ? $schedule->manual_school_year ?? 'N/A' : '-' }}</td>
                            <td>{{ in_array(optional($schedule->file)->file_name, ['COR', 'COG']) ? $schedule->manual_semester ?? 'N/A' : '-' }}</td>
                            <td>{{ $schedule->copies }}</td>
                            <td class="status-{{ $schedule->status }}">{{ ucfirst($schedule->status) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
