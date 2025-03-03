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
    @if($schedules->isEmpty())
        <p class="text-gray-600 text-center mt-4">No schedule requests available.</p>
    @else
        <div class="card bg-white bg-opacity-30 backdrop-blur-sm shadow-lg rounded-lg">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="bg-gray-200">
                        <tr>
                            <th>Student</th>
                            <th>File</th>
                            <th>Preferred Date</th>
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
                                <td>{{ ucfirst($schedule->preferred_time) }}</td>
                                <td>{{ $schedule->reason }}</td>                        
                                <td>{{ $schedule->copies }}</td>
                                <td class="status-cell">
                                    <span class="badge bg-{{ $schedule->status == 'approved' ? 'success' : ($schedule->status == 'rejected' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($schedule->status) }}
                                    </span>
                                </td>
                                <td>
                                    <form action="{{ route('schedules.approve', $schedule->id) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('schedules.reject', $schedule->id) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </form>
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
    @endif
</div>

<style>
    /* Improved Table Styling */
    .table {
        border-collapse: collapse;
        width: 100%;
    }

    .table-bordered th, .table-bordered td {
        border: 1px solid #e2e8f0;
        padding: 10px;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.03);
    }

    .status-cell {
        text-align: center;
    }

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

    .btn {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-weight: 500;
    }

    .table-responsive {
        overflow-x: auto;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection
