<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - File Retrieval System</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Use the same CSS as Admin Dashboard -->
    <link href="{{ asset('css/responsive-admin.css') }}" rel="stylesheet">
    
    <link rel="icon" href="{{ asset('images/PRMSU.png') }}" type="image/x-icon">
</head>
<body>
    <x-messages/>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <button class="close-sidebar" id="closeSidebarBtn">×</button>
            
            <!-- PRMSU Logo -->
            <div class="sidebar-logo text-center p-3">
                <img src="{{ asset('images/PRMSU.png') }}" alt="PRMSU Logo">
            </div>

            <div class="p-3">
                <h3 class="text-white">Student Dashboard</h3>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}" 
                            href="{{ route('student.dashboard') }}">Dashboard</a>                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('student.schedules.create') }}">Create Schedule</a>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="nav-link text-white border-0 bg-transparent w-100 text-start">
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="content-area" id="contentArea">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
                <div class="container d-flex justify-content-between align-items-center">
                    <!-- Hamburger Button -->
                    <button class="hamburger-btn" id="hamburgerBtn">
                        @include('components.icons.hamburger')
                    </button>

                    <!-- Centered Title -->
                    <div class="mx-auto text-center">
                        <span class="navbar-brand m-0" style="font-weight: bold;">
                            File-Retrieval System
                        </span>
                    </div>
                    
                    <!-- Empty Placeholder for Alignment -->
                    <div style="width: 30px;"></div>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="p-4">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Sidebar Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const contentArea = document.getElementById('contentArea');
            const hamburgerBtn = document.getElementById('hamburgerBtn');
            const closeSidebarBtn = document.getElementById('closeSidebarBtn');

            function toggleSidebar() {
                sidebar.classList.toggle('active');
            }

            hamburgerBtn.addEventListener('click', toggleSidebar);
            closeSidebarBtn.addEventListener('click', toggleSidebar);

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                const isMobile = window.innerWidth <= 768;
                if (isMobile && !sidebar.contains(event.target) && event.target !== hamburgerBtn) {
                    sidebar.classList.remove('active');
                }
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    sidebar.classList.remove('active');
                }
            });
        });
    </script>

</body>
</html>
