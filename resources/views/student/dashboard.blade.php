@extends('layouts.default')

@section('content')
<div class="dashboard-container">
    <div class="page-overlay">
        <div class="dashboard-header">
            <h2 class="dashboard-title">Welcome, {{ $user->first_name }} to Your Dashboard</h2>
            <p class="dashboard-subtitle">
                Here you can manage your schedules and other student-related tasks.
            </p>
        </div>

        <div class="dashboard-grid">
            <!-- Upcoming Schedules -->
            <div class="schedule-card">
                <div class="p-6">
                    <h5 class="stat-card-title">Upcoming Schedules</h5>

                    @if($schedules->isEmpty())
                        <p class="text-gray-500">No upcoming schedules.</p>
                    @else
                        <ul class="schedule-list">
                            @foreach ($schedules as $schedule)
                                <li class="schedule-item">
                                    <strong class="text-lg text-gray-700">{{ $schedule->file->name }}</strong> <br>
                                    <span class="text-sm text-gray-500">Date:</span> 
                                    <span class="text-gray-700">{{ \Carbon\Carbon::parse($schedule->preferred_date)->format('M d, Y') }}</span> <br>
                                    <span class="text-sm text-gray-500">Time:</span>
                                    <span class="text-gray-700">{{ ucfirst($schedule->preferred_time) }}</span> <br>
                                    <span class="text-sm text-gray-500">Status:</span>
                                    <span class="inline-block py-2 px-4 rounded-full text-sm font-semibold 
                                        {{ $schedule->status == 'approved' ? 'bg-green-600' : 'bg-yellow-600' }} 
                                        text-white shadow-md">
                                        {{ ucfirst($schedule->status) }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<link href="{{ asset('css/student.css') }}" rel="stylesheet">