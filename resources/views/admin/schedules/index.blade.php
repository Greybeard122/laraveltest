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
    <form method="GET" action="{{ route('admin.schedules.index') }}" 
        class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 items-end gap-4">

        <!-- File Type Filter -->
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

        <!-- Status Filter -->
        <div>
            <label class="block text-gray-700 font-bold mb-1">Status</label>
            <select name="status" class="form-control">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>

        <!-- Filter & Reset Buttons -->
        <div class="filter-buttons h-full">
            <button type="submit" class="btn btn-primary h-full">
                <i class="fas fa-filter"></i> Apply Filters
            </button>
            <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary h-full">
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
                                    <a href="{{ route('admin.reports.student', $schedule->student->id) }}" class="student-link">
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
/* Apply row emphasis on hover when interacting with buttons */
.table tbody tr:hover {
    background-color: rgba(59, 130, 246, 0.1); /* Light blue background */
    transition: background-color 0.3s ease-in-out;
}
/* Ensure cursor changes when hovering over the row */
.table tbody tr {
    transition: background-color 0.3s ease-in-out;
    cursor: pointer;
}
.button-container {
    display: flex;
    gap: 0.25rem; /* Reduced gap */
    align-items: center;
}

.button-container form {
    margin: 0; /* Remove default form margins */
}

.button-container .btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.25rem 0.5rem; /* Reduced padding */
    font-size: 0.75rem; /* Smaller font size */
    line-height: 1.2;
    gap: 0.25rem; /* Small gap between icon and text */
    height: auto; /* Allow natural height */
    white-space: nowrap; /* Prevent text wrapping */
    transition: all 0.3s ease-in-out;
}

.button-container .btn i {
    font-size: 0.75rem; /* Smaller icon size */
    margin-right: 0.25rem; /* Slight spacing between icon and text */
}

/* Ensure buttons don't stretch table cells */
.table td .button-container {
    display: flex;
    justify-content: flex-start;
    align-items: center;
}
/* Accept Button - Darker Green on Hover */
.btn-success:hover {
    background-color: #059669; /* Darker Green */
    box-shadow: 0 4px 8px rgba(5, 150, 105, 0.3);
    transform: scale(1.05);
}

/* Reject Button - Darker Red on Hover */
.btn-danger:hover {
    background-color: #dc2626; /* Darker Red */
    box-shadow: 0 4px 8px rgba(220, 38, 38, 0.3);
    transform: scale(1.05);
}

/* Optional - Add a smooth press effect */
.btn-success:active,
.btn-danger:active {
    transform: scale(0.98);
}

/* filter buttons */
    .filter-buttons {
    display: flex;
    gap: 0.5rem;
    align-items: flex-end; 
}

.filter-buttons .btn {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 42px; 
}

    /* Student Name Hover Effect */
    
.student-link {
    display: inline-block;
    padding: 6px 10px;
    border-radius: 4px;
    background-color: transparent;
    transition: background-color 0.3s, transform 0.2s, color 0.3s;
    font-weight: 600;
    text-decoration: none;
    color: #1e40af; /* Deep blue */
}

.student-link:hover {
    background-color: rgba(59, 130, 246, 0.15); /* Light blue background */
    color: #1e3a8a; /* Darker blue */
    transform: translateY(-2px);
    text-shadow: 0 1px 3px rgba(59, 130, 246, 0.4);
}
    /* Improved Filter Box */
    .filter-box {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    /* Form Controls */
    .form-control {
        width: 100%;
        padding: 0.75rem;
        border-radius: 6px;
        border: 1px solid #cbd5e1;
        transition: border 0.2s;
        height: 42px;
    }

    .form-control:focus {
        border-color: #6366f1;
        outline: none;
        box-shadow: 0 0 6px rgba(99, 102, 241, 0.3);
    }

    /* Status Badges */
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

    /* Button Styling */
    .btn {
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-weight: 500;
    }
    .table td,
    .table th {
        position: relative;
    }
    
    .table td:not(:last-child)::after,
    .table th:not(:last-child)::after {
        content: '';
        position: absolute;
        right: 0;
        top: 25%;
        height: 50%;
        width: 1px;
        background-color: var(--border-color);
    }
    
    
    /* Ensure responsive behavior is maintained */
    @media (max-width: 768px) {
        .table td:not(:last-child)::after,
        .table th:not(:last-child)::after {
            display: none;
        }
    }.student-link-highlight {
    background-color: rgba(59, 130, 246, 0.15) !important;
    color: #1e3a8a !important;
    text-shadow: 0 1px 3px rgba(59, 130, 246, 0.4) !important;
    /* Remove the transform to avoid the element jumping */
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // For each approve and reject button
    document.querySelectorAll('.btn-success, .btn-danger').forEach(button => {
        // Add mouseenter event
        button.addEventListener('mouseenter', function() {
            // Find the parent row
            const row = this.closest('tr');
            if (row) {
                // Find the student link within this row
                const studentLink = row.querySelector('.student-link');
                if (studentLink) {
                    // Add highlight class to the student link only
                    studentLink.classList.add('student-link-highlight');
                }
            }
        });
        
        // Add mouseleave event
        button.addEventListener('mouseleave', function() {
            // Find the parent row
            const row = this.closest('tr');
            if (row) {
                // Find the student link
                const studentLink = row.querySelector('.student-link');
                if (studentLink) {
                    // Remove highlight class
                    studentLink.classList.remove('student-link-highlight');
                }
            }
        });
    });
});
</script>
@endsection



