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
<div class="schedule-table-container">
    <div class="schedule-table-responsive">
        <table class="schedule-table">
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
                                <a href="{{ route('admin.reports.student', $schedule->student->id) }}" class="text-blue-600 hover:text-blue-800 hover:underline">
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
</div>

</div>

<style>
    /* Schedule Table Improvements */
    .table-container {
      overflow-x: visible;
      border-radius: 0.5rem;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.08);
      background-color: white;
      margin-bottom: 2rem;
    }
    
    .schedule-table {
      width: 100%;
      table-layout: fixed;
      border-collapse: separate;
      border-spacing: 0;
      overflow: hidden;
    }
    
    .schedule-table thead {
      background-color: #f8f9fa;
      position: sticky;
      top: 0;
      z-index: 10;
    }
    
    .schedule-table th {
      padding: 1rem 0.75rem;
      vertical-align: middle;
      border-bottom: 2px solid #dee2e6;
      font-weight: 600;
      white-space: nowrap;
      text-align: left;
      color: #495057;
      transition: background-color 0.2s;
    }
    
    .schedule-table th:hover {
      background-color: #e9ecef;
    }
    
    .schedule-table td {
      padding: 0.875rem 0.75rem;
      vertical-align: middle;
      border-bottom: 1px solid #e9ecef;
      word-wrap: break-word;
      max-width: 100%;
      transition: background-color 0.2s;
    }
    
    .schedule-table tr:hover td {
      background-color: rgba(0, 123, 255, 0.03);
    }
    
    /* Column width adjustments - revised */
    .schedule-table th:nth-child(1), .schedule-table td:nth-child(1) { width: 15%; } /* Student */
    .schedule-table th:nth-child(2), .schedule-table td:nth-child(2) { width: 12%; } /* File */
    .schedule-table th:nth-child(3), .schedule-table td:nth-child(3) { width: 8%; } /* Date */
    .schedule-table th:nth-child(4), .schedule-table td:nth-child(4) { width: 6%; } /* Time */
    .schedule-table th:nth-child(5), .schedule-table td:nth-child(5) { width: 18%; } /* Reason */
    .schedule-table th:nth-child(6), .schedule-table td:nth-child(6) { width: 10%; } /* School Year */
    .schedule-table th:nth-child(7), .schedule-table td:nth-child(7) { width: 9%; } /* Semester */
    .schedule-table th:nth-child(8), .schedule-table td:nth-child(8) { width: 9%; } /* Status */
    .schedule-table th:nth-child(9), .schedule-table td:nth-child(9) { width: 13%; } /* Actions */
    
    /* Improve text handling in cells */
    .stack-text {
      display: flex;
      flex-direction: column;
    }
    
    .student-name span {
      display: block;
      white-space: normal;
    }
    
    .prevent-break {
      word-break: break-word;
      white-space: normal;
      overflow-wrap: break-word;
    }
    
    /* Better action buttons layout */
    .button-container {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }
    
    .button-container .btn {
      width: 100%;
      padding: 0.375rem 0.75rem;
      font-size: 0.875rem;
      border-radius: 0.25rem;
      white-space: nowrap;
      transition: all 0.15s ease-in-out;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    .button-container .btn:hover {
      transform: translateY(-1px);
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .button-container .btn i {
      margin-right: 0.25rem;
    }
    
    /* Status styles with improved visual treatment */
    .status-pending, .status-approved, .status-rejected, .status-completed {
      font-weight: 600;
      display: inline-block;
      padding: 0.25rem 0.5rem;
      border-radius: 0.25rem;
      white-space: nowrap;
      text-align: center;
    }
    
    .status-pending {
      color: #856404;
      background-color: #fff3cd;
      border: 1px solid #ffeeba;
    }
    
    .status-approved {
      color: #155724;
      background-color: #d4edda;
      border: 1px solid #c3e6cb;
    }
    
    .status-rejected {
      color: #721c24;
      background-color: #f8d7da;
      border: 1px solid #f5c6cb;
    }
    
    .status-completed {
      color: #0c5460;
      background-color: #d1ecf1;
      border: 1px solid #bee5eb;
    }
    
    /* Sort indicators for table headers */
    .schedule-table th a {
      color: inherit;
      text-decoration: none;
      display: flex;
      align-items: center;
    }
    
    .schedule-table th a::after {
      content: '↕';
      margin-left: 0.25rem;
      font-size: 0.75rem;
      opacity: 0.5;
    }
    
    /* Responsive changes for smaller screens */
    @media (max-width: 992px) {
      .table-responsive {
        max-width: 100%;
        overflow-x: auto;
      }
      
      .schedule-table {
        min-width: 900px;
      }
    }
    
    @media (max-width: 768px) {
      .schedule-table th, .schedule-table td {
        padding: 0.5rem;
      }
    }
    </style>    
@endsection
