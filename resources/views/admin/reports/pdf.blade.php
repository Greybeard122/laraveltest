<!DOCTYPE html>
<html>
<head>
    <title>Schedule Report</title>
</head>
<body>
    <h2>Schedule Report</h2>
    <table border="1" cellpadding="5" cellspacing="0">
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
                    <td>{{ ucfirst($schedule->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
