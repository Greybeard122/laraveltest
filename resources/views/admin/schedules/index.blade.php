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
               
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary ms-2">
                        <i class="fas fa-undo"></i>Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="alert alert-info mb-4">
        <i class="fas fa-info-circle me-2"></i> All schedule requests are kept on this page for 7 days to allow status changes. Older requests are saved on Report Page.
    </div>

    @if(session('success'))
        <div class="alert alert-success fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    

    <!-- Schedules Table -->
    <div class="card bg-white bg-opacity-30 backdrop-blur-sm shadow-lg rounded-lg">
        <div class="card-header d-flex justify-content-between align-items-center  bg-opacity-80 text-white py-3">
            <h5 class="m-0">Schedule Requests</h5>
           
        </div>
        <div class="table-responsive">
            <table class="table" id="schedulesTable">
                <thead>
                    <tr>
                        <th><a href="#" class="text-dark text-decoration-none" onclick="sortTable(0)">Student ⬍</a></th>
                        <th><a href="#" class="text-dark text-decoration-none" onclick="sortTable(1)">File ⬍</a></th>
                        <th><a href="#" class="text-dark text-decoration-none" onclick="sortTable(2)">Date ⬍</a></th>
                        <th><a href="#" class="text-dark text-decoration-none" onclick="sortTable(3)">Time ⬍</a></th>
                        <th><a href="#" class="text-dark text-decoration-none" onclick="sortTable(4)">Status ⬍</a></th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedules as $schedule)
                        <tr class="table-row" id="schedule-{{ $schedule->id }}">
                            <td>
                                @if($schedule->student)
                                    <a href="{{ route('admin.reports.student', $schedule->student->id) }}" 
                                    class="text-blue-600 hover:text-blue-800 hover:underline">
                                        {{ $schedule->student->first_name . ' ' . $schedule->student->last_name }}
                                    </a>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ optional($schedule->file)->file_name ?? 'N/A' }}</td>
                            <td data-date="{{ $schedule->preferred_date }}">
                                {{ \Carbon\Carbon::parse($schedule->preferred_date)->format('M d, Y') }}
                            </td>
                            <td>{{ $schedule->preferred_time }}</td>
                            <td class="capitalize">
                                <span class="status-badge status-{{ $schedule->status }}">
                                    {{ $schedule->status }}
                                </span>
                            </td>
                            <td>
                                <div class="button-container">
                                    <form action="{{ route('schedules.approve', $schedule->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn approve-btn btn-success btn-sm">
                                            <i class="fas fa-check me-1"></i> Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('schedules.reject', $schedule->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn approve-btn btn-danger btn-sm">
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
            <div class="card-footer">
                {{ $schedules->links() }}
            </div>
        @endif
    </div>
</div>

<script>
let sortDirection = 1; // 1 for ascending, -1 for descending

function sortTable(columnIndex) {
    const table = document.getElementById('schedulesTable');
    const tbody = table.getElementsByTagName('tbody')[0];
    const rows = Array.from(tbody.getElementsByTagName('tr'));
    sortDirection *= -1; // Toggle direction

    rows.sort((a, b) => {
        let aContent, bContent;

        // Get cell content
        if (columnIndex === 2) { // Date column
            aContent = new Date(a.cells[columnIndex].getAttribute('data-date'));
            bContent = new Date(b.cells[columnIndex].getAttribute('data-date'));
            return sortDirection * (aContent - bContent);
        } else if (columnIndex === 0) { // Student column
            aContent = a.cells[columnIndex].textContent.trim().toLowerCase();
            bContent = b.cells[columnIndex].textContent.trim().toLowerCase();
            if (aContent === 'n/a') return sortDirection;
            if (bContent === 'n/a') return -sortDirection;
        } else {
            aContent = a.cells[columnIndex].textContent.trim().toLowerCase();
            bContent = b.cells[columnIndex].textContent.trim().toLowerCase();
        }

        return sortDirection * aContent.localeCompare(bContent);
    });

    // Reinsert sorted rows
    rows.forEach(row => tbody.appendChild(row));

    // Update sort indicators
    const headers = table.getElementsByTagName('th');
    for (let i = 0; i < headers.length; i++) {
        const arrow = i === columnIndex 
            ? (sortDirection === 1 ? ' ⬆' : ' ⬇') 
            : ' ⬍';
        if (headers[i].getElementsByTagName('a').length > 0) {
            headers[i].getElementsByTagName('a')[0].innerHTML = 
                headers[i].getElementsByTagName('a')[0].innerHTML.replace(/[⬍⬆⬇]/, arrow);
        }
    }
}

// Add hover effect to sortable columns
document.addEventListener('DOMContentLoaded', function() {
    const sortableHeaders = document.querySelectorAll('th a');
    sortableHeaders.forEach(header => {
        header.addEventListener('mouseover', function() {
            this.style.opacity = '0.7';
        });
        header.addEventListener('mouseout', function() {
            this.style.opacity = '1';
        });
    });
    
    // Auto-hide alert messages after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert:not(.alert-info)');
        alerts.forEach(alert => {
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.style.display = 'none';
            }, 500);
        });
    }, 5000);
    
    // Add status badge styling
    const statusBadges = document.querySelectorAll('.status-badge');
    statusBadges.forEach(badge => {
        if (badge.classList.contains('status-pending')) {
            badge.classList.add('badge', 'bg-warning', 'text-dark');
        } else if (badge.classList.contains('status-approved')) {
            badge.classList.add('badge', 'bg-success');
        } else if (badge.classList.contains('status-rejected')) {
            badge.classList.add('badge', 'bg-danger');
        } else if (badge.classList.contains('status-completed')) {
            badge.classList.add('badge', 'bg-info');
        }
    });
});

// Highlight row on status change
function highlightRow(rowId) {
    const row = document.getElementById(rowId);
    row.classList.add('highlight');
    setTimeout(() => {
        row.classList.remove('highlight');
    }, 2000);
}
</script>

<style>
/* Status Badge Styling */
.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 50rem;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Row Hover Effect */
.table-row {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.table-row:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    z-index: 1;
    position: relative;
}

/* Today's Schedule Highlighting */
.today-schedule {
    background-color: rgba(52, 152, 219, 0.1);
}

/* Tooltip Styling */
[data-tooltip] {
    position: relative;
    cursor: help;
}

[data-tooltip]:before {
    content: attr(data-tooltip);
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    padding: 0.5rem 0.75rem;
    background-color: #2c3e50;
    color: white;
    border-radius: 0.25rem;
    white-space: nowrap;
    opacity: 0;
    visibility: hidden;
    transition: all 0.2s ease;
    z-index: 10;
    font-size: 0.75rem;
    font-weight: normal;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

[data-tooltip]:hover:before {
    opacity: 1;
    visibility: visible;
    bottom: calc(100% + 5px);
}

/* Alert styling improvements */
.alert {
    border-radius: 0.5rem;
    padding: 1rem;
    margin-bottom: 1.5rem;
    border: none;
    display: flex;
    align-items: center;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    transition: opacity 0.5s ease-in-out;
}

.alert i {
    margin-right: 0.75rem;
    font-size: 1.25rem;
}

.alert-success {
    background-color: rgba(46, 204, 113, 0.15);
    border-left: 4px solid #2ecc71;
    color: #27ae60;
}

.alert-info {
    background-color: rgba(52, 152, 219, 0.15);
    border-left: 4px solid #3498db;
    color: #2980b9;
}

.space-x-2 > * + * {
    margin-left: 0.5rem;
}

.btn {
    transition: all 0.2s ease-in-out;
}

.btn:hover {
    transform: translateY(-1px);
}
</style>
@endsection