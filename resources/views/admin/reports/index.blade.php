@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 w-full">
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
                        <th>School Year & Semester</th>
                        <th>Copies</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedules as $schedule)
                        <tr>
                            <td>
                                {{ $schedule->student->first_name ?? 'N/A' }} {{ $schedule->student->last_name ?? '' }}
                            </td>

                            <!-- Show Manual SY/Sem if COR/COG -->
                            <td>
                                @if(in_array(optional($schedule->file)->file_name, ['COR', 'COG']))
                                    {{ $schedule->manual_school_year ?? 'N/A' }} - {{ $schedule->manual_semester ?? 'N/A' }}
                                @else
                                    {{ optional($schedule->file)->file_name ?? 'N/A' }}
                                @endif
                            </td>

                            <td>{{ \Carbon\Carbon::parse($schedule->preferred_date)->format('M d, Y') }}</td>
                            <td>{{ $schedule->preferred_time }}</td>
                            <td>{{ $schedule->reason }}</td>

                            <!-- School Year & Semester -->
                            <td>
                                {{ optional($schedule->schoolYear)->year ?? 'N/A' }} - 
                                {{ $schedule->semester_name ?? 'N/A' }}
                            </td> 
                            <td>{{ $schedule->copies }}</td>    
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
