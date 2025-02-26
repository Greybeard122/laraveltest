@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 w-full">
    <!-- Title and Archived Link -->
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-semibold">Schedule Management</h2>
        <a href="{{ route('admin.reports.index') }}" class="archived-link">
            <i class="fas fa-archive"></i> View Report Page
        </a>
    </div>

    <!-- Filter Form -->
    <div class="card mb-4 bg-white bg-opacity-30 backdrop-blur-sm shadow-lg rounded-lg p-4">
        <form class="grid grid-cols-1 md:grid-cols-4 gap-4" method="GET" action="{{ route('admin.schedules.index') }}">
            <div>
                <label class="block text-gray-700 font-bold mb-1">File Type</label>
                <select class="form-control" name="file_id">
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
                <select class="form-control" name="status">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-1">School Year</label>
                <select class="form-control" name="school_year_id">
                    <option value="">All School Years</option>
                    @foreach($schoolYears as $year)
                        <option value="{{ $year->id }}" {{ request('school_year_id') == $year->id ? 'selected' : '' }}>
                            {{ $year->year }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-1">Semester</label>
                <select class="form-control" name="semester_id">
                    <option value="">All Semesters</option>
                    @foreach($semesters as $semester)
                        <option value="{{ $semester->id }}" {{ request('semester_id') == $semester->id ? 'selected' : '' }}>
                            {{ $semester->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="btn-filter">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('admin.schedules.index') }}" class="btn-clear">
                    <i class="fas fa-undo"></i> Clear
                </a>
            </div>
        </form>
    </div>

    <div class="alert alert-info mb-4">
        <i class="fas fa-info-circle me-2"></i> All schedule requests are kept on this page for 7 days to allow status changes. Older requests are saved on the Report Page.
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    <!-- Schedules Table -->
    <div class="card bg-white bg-opacity-30 backdrop-blur-sm shadow-lg rounded-lg p-4">
        <div class="overflow-x-auto">
            <table class="schedule-table w-full border-collapse">
                <thead>
                    <tr class="bg-gray-200 text-left">
                        <th><a href="{{ route('admin.schedules.index', array_merge(request()->all(), ['sort' => 'student_id', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}">Student ⬍</a></th>
                        <th>File</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Reason</th>
                        <th>School Year</th>
                        <th>Semester</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedules as $schedule)
                        <tr class="hover:bg-gray-100">
                            <td>
                                @if($schedule->student)
                                    <a href="{{ route('admin.reports.student', $schedule->student->id) }}" class="text-blue-600 hover:underline">
                                        {{ $schedule->student->first_name }} {{ $schedule->student->last_name }}
                                    </a>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ optional($schedule->file)->file_name ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($schedule->preferred_date)->format('M d, Y') }}</td>
                            <td>{{ $schedule->preferred_time }}</td>
                            <td class="truncate max-w-[200px]">{{ $schedule->reason }}</td>
                            <td>{{ optional($schedule->schoolYear)->year ?? 'N/A' }}</td>
                            <td>{{ optional($schedule->semester)->name ?? 'N/A' }}</td>
                            <td class="status-{{ $schedule->status }}">{{ ucfirst($schedule->status) }}</td>
                            <td>
                                <form action="{{ route('schedules.approve', $schedule->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn-approve">Approve</button>
                                </form>
                                <form action="{{ route('schedules.reject', $schedule->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn-reject">Reject</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
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

<style>
    .schedule-table {
        table-layout: auto;
        width: 100%;
    }

    .schedule-table th, .schedule-table td {
        padding: 10px;
        text-align: left;
        border: 1px solid #ddd;
        white-space: normal;
        word-break: break-word;
    }

    .schedule-table th {
        background-color: #f8f9fa;
        font-weight: bold;
    }

    .truncate {
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .btn-filter {
        background-color: #007bff;
        color: white;
        padding: 8px 12px;
        border-radius: 5px;
    }

    .btn-clear {
        background-color: #6c757d;
        color: white;
        padding: 8px 12px;
        border-radius: 5px;
    }

    .btn-approve {
        background-color: #28a745;
        color: white;
        padding: 6px 10px;
        border-radius: 5px;
    }

    .btn-reject {
        background-color: #dc3545;
        color: white;
        padding: 6px 10px;
        border-radius: 5px;
    }
</style>
@endsection
