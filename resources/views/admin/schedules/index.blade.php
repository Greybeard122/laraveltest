@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="text-2xl font-bold mb-4">Today's Appointments</h2>

    <!-- Filter Form -->
    <div class="card filter-box mb-4 p-4 bg-white shadow rounded">
        <form method="GET" action="{{ route('admin.schedules.today') }}" class="row g-3">
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
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('admin.schedules.today') }}" class="btn btn-secondary ms-2">
                    <i class="fas fa-undo"></i> Clear
                </a>
            </div>
        </form>
    </div>

    @if ($schedules->isEmpty())
        <p class="text-gray-500">No appointments for today.</p>
    @else
        <div class="table-responsive bg-white p-4 shadow rounded">
            <table class="table">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>File</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($schedules as $schedule)
                        <tr>
                            <td>{{ $schedule->student->first_name }} {{ $schedule->student->last_name }}</td>
                            <td>
                                {{ optional($schedule->file)->file_name ?? 'N/A' }}
                                @if(in_array(optional($schedule->file)->file_name, ['COR', 'COG']) && $schedule->manual_school_year && $schedule->manual_semester)
                                    <br>
                                    <small class="text-gray-500">{{ $schedule->manual_school_year }} - {{ $schedule->manual_semester }}</small>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($schedule->preferred_date)->format('M d, Y') }}</td>
                            <td>{{ ucfirst($schedule->preferred_time) }}</td>
                            <td>
                                <span class="badge bg-{{ $schedule->status == 'approved' ? 'success' : ($schedule->status == 'rejected' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($schedule->status) }}
                                </span>
                            </td>
                            <td>
                                @if($schedule->status == 'pending')
                                    <form action="{{ route('schedules.approve', $schedule->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('schedules.reject', $schedule->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-500">No Actions Available</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection


<style>
    /* Enhanced Schedule Management Page Styles */

/* Main container styles */
.container {
    max-width: 1400px;
    margin: 0 auto;
}

/* Header area with improved spacing */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #e2e8f0;
}

.page-header h2 {
    margin: 0;
    color: #2d3748;
}

.archived-link {
    display: inline-flex;
    align-items: center;
    color: #4a5568;
    text-decoration: none;
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    transition: all 0.2s;
    background-color: #f7fafc;
    border: 1px solid #e2e8f0;
}

.archived-link:hover {
    background-color: #edf2f7;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.archived-link i {
    margin-right: 0.5rem;
}

/* Info Alert with improved styling */
.info-alert {
    background-color: #ebf8ff;
    border-left: 4px solid #4299e1;
    color: #2b6cb0;
    padding: 1rem;
    margin-bottom: 1.5rem;
    border-radius: 0.375rem;
    display: flex;
    align-items: center;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.info-alert i {
    font-size: 1.25rem;
    margin-right: 0.75rem;
}

/* Success Alert styling */
.success-alert {
    background-color: #f0fff4;
    border-left: 4px solid #48bb78;
    color: #2f855a;
    padding: 1rem;
    margin-bottom: 1.5rem;
    border-radius: 0.375rem;
    display: flex;
    align-items: center;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.success-alert i {
    font-size: 1.25rem;
    margin-right: 0.75rem;
}

/* Filter box with improved visuals */
.filter-box {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 0.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    margin-bottom: 1.5rem;
    transition: box-shadow 0.3s;
    border: 1px solid #e2e8f0;
}

.filter-box:hover {
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
}

.filter-box .card-body {
    padding: 1.25rem;
}

.filter-box label {
    font-weight: 600;
    color: #4a5568;
    margin-bottom: 0.5rem;
    display: block;
}

.filter-box select {
    width: 100%;
    border-radius: 0.375rem;
    border: 1px solid #e2e8f0;
    padding: 0.625rem;
    background-color: white;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    transition: border-color 0.2s, box-shadow 0.2s;
}

.filter-box select:focus {
    border-color: #4299e1;
    box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.15);
    outline: none;
}

.filter-box .filter-buttons {
    display: flex;
    gap: 0.5rem;
}

.filter-box button {
    font-weight: 500;
    display: flex;
    align-items: center;
    justify-content: center;
}

.filter-box button i {
    margin-right: 0.5rem;
}

/* Enhanced Schedule Table */
.schedule-table-container {
    background-color: white;
    border-radius: 0.5rem;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    overflow: hidden;
    margin-bottom: 1.5rem;
    border: 1px solid #e2e8f0;
}

.schedule-table-responsive {
    overflow-x: auto;
    max-height: 70vh;
}

.schedule-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.schedule-table th {
    position: sticky;
    top: 0;
    background-color: #f7fafc;
    padding: 1rem 0.75rem;
    font-weight: 600;
    text-align: left;
    color: #4a5568;
    border-bottom: 2px solid #e2e8f0;
    z-index: 10;
}

.schedule-table th a {
    color: #4a5568;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.schedule-table th a:hover {
    color: #2d3748;
}

.schedule-table td {
    padding: 0.875rem 0.75rem;
    border-bottom: 1px solid #edf2f7;
    vertical-align: middle;
}

.schedule-table tr:hover {
    background-color: #f7fafc;
}

/* Student name links */
.schedule-table a.student-link {
    color: #3182ce;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s;
}

.schedule-table a.student-link:hover {
    color: #2c5282;
    text-decoration: underline;
}

/* Status colors with improved visibility */
.status-pending {
    font-weight: 600;
    color: #dd6b20;
    background-color: #fffaf0;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    display: inline-block;
}

.status-approved {
    font-weight: 600;
    color: #2f855a;
    background-color: #f0fff4;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    display: inline-block;
}

.status-rejected {
    font-weight: 600;
    color: #c53030;
    background-color: #fff5f5;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    display: inline-block;
}

.status-completed {
    font-weight: 600;
    color: #2b6cb0;
    background-color: #ebf8ff;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    display: inline-block;
}

/* Better action buttons */
.button-container {
    display: flex;
    gap: 0.5rem;
}

.button-container .btn {
    padding: 0.375rem 0.75rem;
    display: inline-flex;
    align-items: center;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.2s;
}

.button-container .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.button-container .btn i {
    margin-right: 0.375rem;
}

/* Responsive Adjustments */
@media (max-width: 992px) {
    .schedule-table-responsive {
        max-width: 100%;
        overflow-x: auto;
    }
}

@media (max-width: 768px) {
    .button-container {
        flex-direction: column;
    }
    
    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .filter-box .row {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .filter-box .col-md-3 {
        width: 100%;
    }
}
</style>