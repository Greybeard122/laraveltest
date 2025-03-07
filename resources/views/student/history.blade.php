@extends('layouts.default')

@section('content')
<div class="dashboard-container">
    <div class="page-overlay">
        <div class="dashboard-header">
            <h2 class="dashboard-title">Completed Requests</h2>
            <p class="dashboard-subtitle">
                Here is a list of your past requests.
            </p>
        </div>

        <div class="dashboard-grid">
            <div class="schedule-card">
                <div class="p-6">
                    <h5 class="stat-card-title">Past Requests</h5>

                    @if($completedSchedules->isEmpty())
                        <p class="text-gray-500">No completed requests.</p>
                    @else
                        <ul class="schedule-list">
                            @foreach ($completedSchedules as $schedule)
                                <li class="schedule-item">
                                    <strong class="text-lg text-gray-700">{{ $schedule->file->name }}</strong> <br>
                                    <span class="text-sm text-gray-500">Date:</span> 
                                    <span class="text-gray-700">{{ \Carbon\Carbon::parse($schedule->preferred_date)->format('M d, Y') }}</span> <br>
                                    <span class="text-sm text-gray-500">Time:</span>
                                    <span class="text-gray-700">{{ ucfirst($schedule->preferred_time) }}</span> <br>
                                    <span class="text-sm text-gray-500">Status:</span>
                                    <span class="inline-block py-2 px-4 rounded-full text-sm font-semibold 
                                        {{ $schedule->status == 'approved' ? 'bg-approved' : 'bg-rejected' }} 
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