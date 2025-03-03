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
                                       class="text-purple-600 hover:text-purple-800 transition">
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
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .filter-box .form-control:focus {
        border-color: #805ad5;
        box-shadow: 0 0 0 3px rgba(128, 90, 213, 0.25);
    }

    .filter-box .btn {
        padding: 0.625rem 1rem;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .filter-box .btn-primary {
        background-color: #805ad5;
        border: none;
    }

    .filter-box .btn-primary:hover {
        background-color: #6b46c1;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(107, 70, 193, 0.2);
    }

    .filter-box .btn-secondary {
        background-color: #e2e8f0;
        color: #4a5568;
        border: none;
    }

    .filter-box .btn-secondary:hover {
        background-color: #cbd5e0;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    /* Enhanced Student Link Hover Effect */
    .text-purple-600 {
        position: relative;
        display: inline-block;
        font-weight: 600;
        transition: all 0.3s ease;
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        z-index: 1;
    }

    .text-purple-600::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: transparent;
        border-radius: 0.375rem;
        z-index: -1;
        transition: all 0.3s ease;
        transform: scale(0.8);
        opacity: 0;
    }

    .text-purple-600:hover {
        color: #6b46c1;
        transform: translateY(-2px);
    }

    .text-purple-600:hover::before {
        background-color: rgba(128, 90, 213, 0.1);
        transform: scale(1);
        opacity: 1;
        box-shadow: 0 4px 8px rgba(107, 70, 193, 0.15);
    }

    /* Responsive adjustments for filter box */
    @media (max-width: 768px) {
        .filter-box form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .filter-box .flex {
            flex-direction: column;
            width: 100%;
        }
        
        .filter-box .btn {
            width: 100%;
            margin-bottom: 0.5rem;
        }
    }

    /* Column Separators */
    .border-right {
        border-right: 1px solid #e2e8f0;
    }

    /* Card and Table Enhancements */
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.07);
    }

    .table-bordered th, .table-bordered td {
        padding: 12px;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(249, 250, 251, 0.7);
    }

    .hover\:bg-gray-100:hover {
        background-color: #f3f4f6 !important;
    }

    /* Status Badges Enhancement */
    .status-cell {
        text-align: center;
    }

    .badge {
        padding: 0.4em 0.75em;
        font-size: 0.75em;
        font-weight: 600;
        border-radius: 0.375rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .bg-success { 
        background-color: #10b981; 
        color: white;
    }
    
    .bg-danger { 
        background-color: #ef4444; 
        color: white;
    }
    
    .bg-warning { 
        background-color: #f59e0b; 
        color: white;
    }

    /* Action Button Improvements */
    .button-container {
    display: flex;
    gap: 8px; /* Space between buttons */
    justify-content: center; /* Center align buttons */
    flex-wrap: wrap; /* Ensure wrapping on smaller screens */
}

.button-container .btn {
    min-width: 100px; /* Ensures buttons have enough width */
    padding: 0.4rem 1rem;
}


    .button-container .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .btn-success {
        background-color: #10b981;
    }

    .btn-success:hover {
        background-color: #059669;
    }

    .btn-danger {
        background-color: #ef4444;
    }

    .btn-danger:hover {
        background-color: #dc2626;
    }

    .btn-sm {
        font-size: 0.8125rem;
        padding: 0.4rem 0.75rem;
    }

    /* Pagination Enhancement */
    .pagination-container {
        padding: 1rem;
    }

    /* Alert Enhancement */
    .alert {
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        border-left: 4px solid transparent;
    }

    .alert-info {
        background-color: rgba(96, 165, 250, 0.1);
        border-left-color: #60a5fa;
        color: #1e40af;
    }

    .alert-success {
        background-color: rgba(16, 185, 129, 0.1);
        border-left-color: #10b981;
        color: #065f46;
    }

    /* Title and Link Enhancement */
    .text-2xl {
        color: #1e293b;
        letter-spacing: -0.025em;
    }

    .archived-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #6b7280;
        font-weight: 500;
        text-decoration: none;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        transition: all 0.2s ease;
    }

    .archived-link:hover {
        background-color: #f3f4f6;
        color: #4b5563;
        transform: translateY(-2px);
    }
</style>
@endsection