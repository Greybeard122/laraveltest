@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="text-2xl font-bold mb-4">Schedule Management (Temporary Storage - 7 Days)</h2>

    <!-- Filter Form -->
    <div class="card filter-box mb-4 p-4 bg-white shadow rounded">
        <form method="GET" action="{{ route('admin.schedules.index') }}" class="row g-3">
            <div class="col-md-3">
                <label>Search by Name</label>
                <input type="text" class="form-control" name="search" placeholder="Enter student name" value="{{ request('search') }}">
            </div>
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
                </select>
            </div>
            <div class="col-md-3">
                <label>From Date</label>
                <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-3">
                <label>To Date</label>
                <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary ms-2">
                    <i class="fas fa-undo"></i> Clear
                </a>
            </div>
        </form>
    </div>

    @if ($schedules->isEmpty())
        <p class="text-gray-500">No schedule requests found within the last 7 days.</p>
    @else
        <div class="table-responsive bg-white p-4 shadow rounded">
            <table class="table">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>File</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Reason</th>
                        <th>School Year & Semester</th>
                        <th>Copies</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($schedules as $schedule)
                        <tr>
                            <td>{{ $schedule->student->first_name }} {{ $schedule->student->last_name }}</td>
                            <td>
                                {{ optional($schedule->file)->file_name ?? 'N/A' }}
                                @if(in_array(optional($schedule->file)->file_name, ['COR', 'COG']) && $schedule->manual_school_year && $schedule->manual_semester)
                                    <br>
                                    <small class="text-gray-500">{{ $schedule->manual_school_year }} - {{ $schedule->manual_semester }}</small>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($schedule->preferred_date)->format('M d, Y') }}</td>
                            <td>{{ ucfirst($schedule->preferred_time) }}</td>
                            <td>{{ $schedule->reason }}</td>
                            <td>{{ optional($schedule->schoolYear)->year ?? 'N/A' }} - {{ optional($schedule->semester)->name ?? 'N/A' }}</td>
                            <td>{{ $schedule->copies }}</td>
                            <td>
                                <span class="badge bg-{{ $schedule->status == 'approved' ? 'success' : ($schedule->status == 'rejected' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($schedule->status) }}
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('schedules.approve', $schedule->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                </form>
                                <form action="{{ route('schedules.reject', $schedule->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
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
    @endif
</div>
<style>
@endsection
