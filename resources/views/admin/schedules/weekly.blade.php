@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="text-2xl font-bold mb-4">Weekly Schedule</h2>

    @if($schedules->isEmpty())
        <p class="text-gray-600">No approved appointments for this week.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>File</th>
                    <th>Preferred Date</th>
                    <th>Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($schedules as $schedule)
                    <tr>
                        <td>{{ $schedule->student->first_name }} {{ $schedule->student->last_name }}</td>
                        <td>{{ optional($schedule->file)->file_name ?? 'No File' }}</td>
                        <td>{{ $schedule->preferred_date }}</td>
                        <td>{{ ucfirst($schedule->preferred_time) }}</td>
                        <td>{{ ucfirst($schedule->status) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $schedules->links() }}
    @endif
</div>
@endsection
