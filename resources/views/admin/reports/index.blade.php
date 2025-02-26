@extends('layouts.admin')
@section('content')
<div class="container mx-auto px-4 w-full">
    <!-- Title -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-2xl font-semibold">Schedule Reports</h2>
        <a href="{{ route('admin.schedules.index') }}" class="archived-link">
            <i class="fas fa-calendar"></i> View Schedule Management
        </a>
    </div>

    <!-- Reports Table -->
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
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schedules as $schedule)
                        <tr>
                            <td>{{ optional($schedule->student)->first_name }} {{ optional($schedule->student)->last_name }}</td>
                            <td>{{ optional($schedule->file)->file_name ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($schedule->preferred_date)->format('M d, Y') }}</td>
                            <td>{{ $schedule->preferred_time }}</td>
                            <td>{{ $schedule->reason }}</td>
                            <td>{{ optional($schedule->schoolYear)->year ?? $schedule->school_year ?? 'N/A' }}</td>
                            <td>{{ optional($schedule->semester)->name ?? (is_numeric($schedule->semester) ? ($schedule->semester == 1 ? '1st Semester' : '2nd Semester') : $schedule->semester) ?? 'N/A' }}</td>
                            <td class="status-{{ $schedule->status }}">{{ ucfirst($schedule->status) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center">No reports found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($schedules->hasPages())
        <div class="p-4 flex justify-center">
            {{ $schedules->links('vendor.pagination.tailwind') }}
        </div>
    @endif
</div>
@endsection
<style>
    /* Schedule Table Improvements */
    .table-container {
      overflow-x: visible;
    }
    
    .schedule-table {
      width: 100%;
      table-layout: fixed;
    }
    
    .schedule-table th {
      padding: 0.75rem;
      vertical-align: top;
      border-top: 1px solid #dee2e6;
      background-color: #f8f9fa;
      font-weight: 600;
      white-space: nowrap;
    }
    
    .schedule-table td {
      padding: 0.75rem;
      vertical-align: top;
      border-top: 1px solid #dee2e6;
      word-wrap: break-word;
      max-width: 100%;
    }
    
    /* Column width adjustments - revised */
.schedule-table th:nth-child(1), .schedule-table td:nth-child(1) { width: 14%; } /* Student */
.schedule-table th:nth-child(2), .schedule-table td:nth-child(2) { width: 11%; } /* File */
.schedule-table th:nth-child(3), .schedule-table td:nth-child(3) { width: 8%; } /* Date */
.schedule-table th:nth-child(4), .schedule-table td:nth-child(4) { width: 5%; } /* Time */
.schedule-table th:nth-child(5), .schedule-table td:nth-child(5) { width: 17%; } /* Reason */
.schedule-table th:nth-child(6), .schedule-table td:nth-child(6) { width: 10%; } /* School Year */
.schedule-table th:nth-child(7), .schedule-table td:nth-child(7) { width: 12%; } /* Semester */
.schedule-table th:nth-child(8), .schedule-table td:nth-child(8) { width: 10%; } /* Status -  */
.schedule-table th:nth-child(9), .schedule-table td:nth-child(9) { width: 13%; } /* Actions - */
    
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
      gap: 5px;
    }
    
    .button-container .btn {
      width: 100%;
      padding: 0.25rem 0.5rem;
      font-size: 0.875rem;
      white-space: nowrap;
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