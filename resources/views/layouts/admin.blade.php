<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - File Retrieval System</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/responsive-admin.css') }}" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/PRMSU.png') }}" type="image/x-icon">
</head>
<body>
    <x-messages/>

    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <button class="close-sidebar" id="closeSidebarBtn">
                ×
            </button>
            
            <!-- PRMSU Logo -->
            <div class="sidebar-logo text-center p-3">
                <img src="{{ asset('images/PRMSU.png') }}" alt="PRMSU Logo">
            </div>

            <div class="p-3">
                <h3 class="text-white">Admin Dashboard</h3>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.dashboard') }}">
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.register') }}">
                            Admins
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.schedules.index') }}">
                            Schedules
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.reports.index') }}">
                            Reports
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.students.index') }}">
                            Students
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.files.index') }}">
                            Files
                        </a>
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
            <nav class="navbar navbar-expand-lg navbar-light shadow-sm">
                <div class="container d-flex align-items-center justify-content-between">
                    <!-- Hamburger Button -->
                    <button class="hamburger-btn" id="hamburgerBtn">
                        @include('components.icons.hamburger')
                    </button>
        
                    <!-- Title (Centered) -->
                    <div class="title-container text-white mx-auto">
                        File-Retrieval System
                    </div>
        
                    <!-- Empty Placeholder for Alignment -->
                    <div class="d-none d-lg-block" style="width: 48px;"></div>
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
        sidebar.classList.toggle('collapsed'); 
        contentArea.classList.toggle('full-width'); // Adjust content
    }

    hamburgerBtn.addEventListener('click', toggleSidebar);
    closeSidebarBtn.addEventListener('click', toggleSidebar);

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        const isMobile = window.innerWidth <= 768;
        if (isMobile && !sidebar.contains(event.target) && event.target !== hamburgerBtn) {
            sidebar.classList.add('collapsed');
            contentArea.classList.add('full-width');
        }
    });

    // Ensure sidebar is collapsed on smaller screens
    window.addEventListener('resize', function() {
        if (window.innerWidth <= 768) {
            sidebar.classList.add('collapsed');
            contentArea.classList.add('full-width');
        } else {
            sidebar.classList.remove('collapsed');
            contentArea.classList.remove('full-width');
        }
    });
});

        let logoutTimer;

    function resetLogoutTimer() {
        clearTimeout(logoutTimer);
        logoutTimer = setTimeout(() => {
            alert('You have been logged out due to inactivity.');

            // Create a form dynamically to send a POST request
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('logout') }}";

            // Add CSRF token input (required for Laravel)
            let csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = "{{ csrf_token() }}";
            form.appendChild(csrfToken);

            document.body.appendChild(form);
            form.submit(); // Submit form to logout properly
        }, 5 * 60 * 1000); // 5 minutes
    }

    document.addEventListener("mousemove", resetLogoutTimer);
    document.addEventListener("keypress", resetLogoutTimer);
    document.addEventListener("click", resetLogoutTimer);
    document.addEventListener("scroll", resetLogoutTimer);

    resetLogoutTimer(); 
    </script>
    @stack('scripts')
</body>
</html>
