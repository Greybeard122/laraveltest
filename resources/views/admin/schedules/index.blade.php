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

    <div class="alert alert-info mb-4">
        <i class="fas fa-info-circle me-2"></i> All schedule requests are kept on this page for 7 days to allow status changes. Older requests are saved on the Report Page.
    </div>

    <!-- Filter Form -->
    <div class="filter-box">
        <h3 class="text-lg font-semibold mb-2">Schedule Filters</h3>
        <form method="GET" action="{{ route('admin.schedules.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
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
                <label class="block text-gray-700 font-bold mb-1">Status</label>
                <select name="status" class="form-control">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter"></i> Apply Filters
                </button>
                <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">
                    <i class="fas fa-undo"></i> Clear
                </a>
            </div>
        </form>
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
                        <th>Copies</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedules as $schedule)
                        <tr>
                            <td>
                                @if($schedule->student)
                                    <a href="{{ route('admin.reports.student', $schedule->student->id) }}" class="text-blue-600 hover:text-blue-800 hover:underline">
                                        {{ $schedule->student->first_name }} {{ $schedule->student->last_name }}
                                    </a>
                                @else
                                    N/A
                                @endif
                            </td>
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
                            <td>{{ $schedule->copies }}</td>
                            <td class="status-cell">
                                <span class="badge bg-{{ $schedule->status == 'approved' ? 'success' : ($schedule->status == 'rejected' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($schedule->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="button-container d-flex gap-2">
                                    <form action="{{ route('schedules.approve', $schedule->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('schedules.reject', $schedule->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if ($schedules->hasPages())
            <div class="pagination-container">
                {{ $schedules->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>
</div>
<style>
@media (min-width: 1024px) { 
    .container {
        max-width: 1200px; 
    }

    .d-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Filter Box Styling */
    .filter-box {
        padding: 2rem;
        margin-bottom: 2rem;
    }

    /* Make filters a single row on large screens */
    .filter-form {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.5rem;
        flex-wrap: nowrap;
    }

    .filter-form div {
        flex: 1;
        min-width: 250px;
    }

    .filter-actions {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-shrink: 0;
    }

    /* Ensure table columns have proper spacing */
    .table th, .table td {
        padding: 1rem 1.5rem;
        text-align: center;
    }

    .button-container {
        justify-content: center;
    }

    .pagination-container {
        padding: 1rem 0;
        display: flex;
        justify-content: center;
    }
}

/* Stack filters on smaller screens */
@media (max-width: 1023px) {
    .filter-form {
        flex-direction: column;
        gap: 1rem;
    }

    .filter-actions {
        justify-content: center;
        width: 100%;
    }
}
</style>
@endsection



