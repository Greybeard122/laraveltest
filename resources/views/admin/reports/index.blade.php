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
                            <td>{{ $schedule->school_year }}</td>
                            <td>{{ ucfirst($schedule->semester) }}</td>
                            <td class="status-{{ $schedule->status }}">{{ ucfirst($schedule->status) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center">No reports found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($schedules->hasPages())
            <div class="p-4 flex justify-center">
                {{ $schedules->links('vendor.pagination.tailwind') }}
            </div>
        @endif
    </div>
</div>
@endsection
