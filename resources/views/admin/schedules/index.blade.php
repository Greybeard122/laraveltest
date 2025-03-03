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
    <div class="card bg-white bg-opacity-50 backdrop-blur-sm shadow-lg rounded-lg p-4">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="bg-gray-200 text-gray-700">
                    <tr>
                        <th class="border-right">Student</th>
                        <th class="border-right">File</th>
                        <th class="border-right">Date</th>
                        <th class="border-right">Time</th>
                        <th class="border-right">Reason</th>
                        <th class="border-right">Copies</th>
                        <th class="border-right">Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedules as $schedule)
                        <tr class="hover:bg-gray-100 transition">
                            <td class="border-right">
                                @if($schedule->student)
                                    <a href="{{ route('admin.reports.student', $schedule->student->id) }}" 
                                       class="student-link">
                                        {{ $schedule->student->first_name }} {{ $schedule->student->last_name }}
                                    </a>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="border-right">
                                {{ optional($schedule->file)->file_name ?? 'N/A' }}
                                @if(in_array(optional($schedule->file)->file_name, ['COR', 'COG']) && $schedule->manual_school_year && $schedule->manual_semester)
                                    <br>
                                    <small class="text-gray-500 text-sm">
                                        {{ $schedule->manual_school_year }} - {{ $schedule->manual_semester }}
                                    </small>
                                @endif
                            </td>                            
                            <td class="border-right">{{ \Carbon\Carbon::parse($schedule->preferred_date)->format('M d, Y') }}</td>
                            <td class="border-right">{{ ucfirst($schedule->preferred_time) }}</td>
                            <td class="border-right">{{ $schedule->reason }}</td>                        
                            <td class="border-right">{{ $schedule->copies }}</td>
                            <td class="status-cell border-right">
                                <span class="badge badge-{{ $schedule->status }}">
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
    /* Enhanced Filter Box */
    .filter-box {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 0.75rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        margin-bottom: 1.5rem;
        padding: 1.5rem;
        transition: box-shadow 0.3s ease;
        border: 1px solid #e2e8f0;
    }

    .filter-box:hover {
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
    }

    .filter-box h3 {
        margin-bottom: 1rem;
        color: #4a5568;
        border-bottom: 2px solid #805ad5;
        padding-bottom: 0.5rem;
        display: inline-block;
    }

    .filter-box .form-control {
        border: 1px solid #e2e8f0;
        padding: 0.625rem 0.75rem;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
    }

    .filter-box .form-control:focus {
        border-color: #805ad5;
        box-shadow: 0 0 0 3px rgba(128, 90, 213, 0.25);
    }

    .filter-box .btn-primary {
        background-color: #805ad5;
        border: none;
    }

    .filter-box .btn-primary:hover {
        background-color: #6b46c1;
        transform: translateY(-2px);
    }

    .filter-box .btn-secondary {
        background-color: #e2e8f0;
        color: #4a5568;
    }

    .filter-box .btn-secondary:hover {
        background-color: #cbd5e0;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .filter-box form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
    }
</style>
@endsection
