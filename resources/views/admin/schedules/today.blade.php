@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="text-2xl font-bold mb-4">Today's Approved Appointments</h2>
    
    @if ($schedules->isEmpty())
        <p class="text-gray-500">No approved appointments for today.</p>
    @else
        <div class="table-responsive bg-white p-4 shadow rounded">
            <table class="table">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>File</th>
                        <th>Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($schedules as $schedule)
                        <tr>
                            <td>{{ $schedule->student->first_name }} {{ $schedule->student->last_name }}</td>
                            <td>{{ $schedule->file->file_name }}</td>
                            <td>{{ strtoupper($schedule->preferred_time) }}</td> <!-- Shows AM or PM only -->
                            <td>
                                <span class="badge bg-success">Approved</span>
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
    @endif
</div>
@endsection
