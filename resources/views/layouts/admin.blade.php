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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <x-messages/>

    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <button class="toggle-sidebar-btn" id="toggleSidebarBtn">&#8592;</button> 
        
            <!-- PRMSU Logo -->
            <div class="sidebar-logo text-center p-3">
                <img src="{{ asset('images/PRMSU.png') }}" alt="PRMSU Logo">
            </div>
        
            <div class="p-3">
                <h3 class="sidebar-title text-white">Admin Dashboard</h3>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i> <span class="nav-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.register') }}">
                            <i class="fas fa-user-shield"></i> <span class="nav-text">Admins</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.schedules.index') }}">
                            <i class="fas fa-calendar-check"></i> <span class="nav-text">Requests</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.reports.index') }}">
                            <i class="fas fa-file-alt"></i> <span class="nav-text">Reports</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.students.index') }}">
                            <i class="fas fa-users"></i> <span class="nav-text">Students</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.files.index') }}">
                            <i class="fas fa-folder"></i> <span class="nav-text">Files</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">
                                <i class="fas fa-sign-out-alt"></i> <span class="nav-text">Logout</span>
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
                    <button class="toggle-sidebar-btn" id="toggleSidebarBtn">
                        &#8592; <!-- Left Arrow by Default -->
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
    const toggleSidebarBtn = document.getElementById('toggleSidebarBtn');

    function toggleSidebar() {
        sidebar.classList.toggle('collapsed');
        contentArea.classList.toggle('full-width');

        // Update Arrow Direction
        toggleSidebarBtn.innerHTML = sidebar.classList.contains('collapsed') ? '&#8594;' : '&#8592;';
    }

    toggleSidebarBtn.addEventListener('click', toggleSidebar);
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
