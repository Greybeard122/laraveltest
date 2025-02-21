@extends('layouts.default')
@section('content')
    <div class="container mx-auto max-w-6xl px-4">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-black drop-shadow-md">
                Admin Dashboard
            </h2>
        </div>
        <!-- Overview Cards (Consistent Style) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <a href="{{ route('admin.schedules.pending') }}" 
               class="group rounded-xl bg-white bg-opacity-50 backdrop-blur-sm p-6 shadow-md transition-all hover:bg-white/30">
                <h5 class="text-lg  text-black drop-shadow-md">
                    Pending Requests
                </h5>
                <h2 class="text-4xl font-bold text-black drop-shadow-lg mt-2">
                    {{ $pendingRequests }}
                </h2>
            </a>

            <a href="{{ route('admin.schedules.today') }}" 
               class="group rounded-xl bg-white bg-opacity-50 backdrop-blur-sm p-6 shadow-md transition-all hover:bg-white/30">
                <h5 class="text-lg  text-black drop-shadow-md">
                    Today's Appointments
                </h5>
                <h2 class="text-4xl font-bold text-black drop-shadow-lg mt-2">
                    {{ $todayAppointments }}
                </h2>
            </a>
        </div>

        <!-- Weekly Schedule Summary -->
        <h2 class="text-2xl font-bold text-black drop-shadow-md mb-6 text-center">
            Weekly Schedule
        </h2>
        <div class="grid grid-cols-7 gap-3">
            @foreach($weeklyCounts as $day => $count)
                <div class="group rounded-xl bg-white bg-opacity-50 backdrop-blur-sm p-4 shadow-md transition-all hover:bg-white/30">
                    <h4 class="text-sm font-bold text-black drop-shadow-md">
                        {{ $day }}
                    </h4>
                    <p class="text-2xl font-bold text-black drop-shadow-lg mt-2">
                        {{ $count }}
                    </p>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-6">
            <a href="{{ route('admin.schedules.weekly') }}" 
               class="inline-block px-6 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition-colors">
                View Full Weekly Schedule
            </a>
        </div>

    </div>
</div>
@endsection
