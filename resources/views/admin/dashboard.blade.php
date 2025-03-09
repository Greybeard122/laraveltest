@extends('layouts.default')

@section('content')
    <div class="container mx-auto max-w-6xl px-4">
        <!-- Admin Dashboard Header (No Hover) -->
        <div class="text-center mt-4 mb-8 fade-in">
            <h2 class="text-3xl font-bold dashboard-title">
                Admin Dashboard
            </h2>
        </div>

        <!-- Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <a href="{{ route('admin.schedules.index') }}" 
               class="group rounded-xl bg-white bg-opacity-50 backdrop-blur-sm p-6 shadow-md transition-all hover:bg-white/30 fade-in pending-requests-card">
                <h5 class="text-lg text-black drop-shadow-md">
                    Pending Requests
                </h5>
                <h2 class="text-4xl font-bold text-black drop-shadow-lg mt-2 pending-requests count-up">
                    {{ $pendingRequests }}
                </h2>
            </a>

            <a href="{{ route('admin.schedules.today') }}" 
               class="group rounded-xl bg-white bg-opacity-50 backdrop-blur-sm p-6 shadow-md transition-all hover:bg-white/30 fade-in today-appointments-card">
                <h5 class="text-lg text-black drop-shadow-md">
                    Today's Appointments
                </h5>
                <h2 class="text-4xl font-bold text-black drop-shadow-lg mt-2 today-appointments count-up">
                    {{ $todayAppointments }}
                </h2>
            </a>
        </div>

        <!-- Weekly Schedule Summary (No Hover) -->
        <div class="text-center mb-6 fade-in">
            <h2 class="text-2xl font-bold dashboard-title">
                Weekly Schedule
            </h2>
        </div>

        <div class="flex flex-col md:flex-row w-full gap-3">
            @foreach($weeklyCounts as $day => $count)
                <div class="group rounded-xl bg-white bg-opacity-50 backdrop-blur-sm p-4 shadow-md transition-all hover:bg-white/30 flex-1 fade-in">
                    <h4 class="text-sm text-black drop-shadow-md">
                        {{ $day }}
                    </h4>
                    <p class="text-2xl font-bold text-black drop-shadow-lg mt-2 weekly-count count-up">
                        {{ $count }}
                    </p>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-6 fade-in">
            <a href="{{ route('admin.schedules.weekly') }}" 
               class="inline-block px-6 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition-colors view-weekly-btn">
                View Full Weekly Schedule
            </a>
        </div>
    </div>

    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Function to animate counting up
            function animateValue(element, start, end, duration) {
                let startTimestamp = null;
                const step = (timestamp) => {
                    if (!startTimestamp) startTimestamp = timestamp;
                    const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                    element.textContent = Math.floor(progress * (end - start) + start);
                    if (progress < 1) {
                        window.requestAnimationFrame(step);
                    }
                };
                window.requestAnimationFrame(step);
            }

            // Animate the card values
            const pendingRequests = document.querySelector('.pending-requests');
            const todayAppointments = document.querySelector('.today-appointments');
            const weeklyCounts = document.querySelectorAll('.weekly-count');

            if (pendingRequests) {
                animateValue(pendingRequests, 0, {{ $pendingRequests }}, 1000);
            }

            if (todayAppointments) {
                animateValue(todayAppointments, 0, {{ $todayAppointments }}, 1000);
            }

            weeklyCounts.forEach((element, index) => {
                const count = parseInt(element.textContent);
                animateValue(element, 0, count, 1000);
            });
        });
    </script>
@endsection
