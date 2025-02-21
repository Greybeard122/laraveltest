@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="text-2xl font-bold mb-4">Today's Appointments</h2>
    
    @if ($schedules->isEmpty())
        <p class="text-gray-500">No appointments for today.</p>
    @else
        <div class="table-responsive bg-white p-4 shadow rounded">
            <table class="table">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>File</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($schedules as $schedule)
                        <tr>
                            <td>{{ $schedule->student->first_name }} {{ $schedule->student->last_name }}</td>
                            <td>{{ $schedule->file->name }}</td>
                            <td>{{ $schedule->preferred_date }}</td>
                            <td>{{ ucfirst($schedule->preferred_time) }}</td>
                            <td>
                                <span class="badge bg-{{ $schedule->status == 'approved' ? 'success' : 'warning' }}">
                                    {{ ucfirst($schedule->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
