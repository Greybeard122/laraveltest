<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - File Retrieval System</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- jQuery & Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom Styles -->
    <link href="{{ asset('css/responsive-admin.css') }}" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/PRMSU.png') }}" type="image/x-icon">
    
    <style>
        /* Main layout */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Navbar (Top Navigation) */
        .navbar-custom {
            background-color: #2d3748; /* Dark gray */
            padding: 12px;
        }

        .navbar-custom .nav-link {
            color: white;
            padding: 8px 16px;
            transition: background 0.3s;
        }

        .navbar-custom .nav-link:hover {
            background-color: #4a5568; /* Slightly lighter gray */
            border-radius: 5px;
        }

        .navbar-custom .nav-logo {
            display: flex;
            align-items: center;
        }

        .navbar-custom .nav-logo img {
            height: 40px;
            margin-right: 10px;
        }

        /* Adjust content area */
        .content-area {
            flex: 1;
            padding: 16px;
        }

        /* Mobile Menu */
        @media (max-width: 992px) {
            .navbar-nav {
                display: none; /* Hide on mobile */
            }
            
            .mobile-nav {
                display: flex;
                justify-content: space-between;
                align-items: center;
                background-color: #2d3748;
                padding: 12px;
            }

            .mobile-nav .hamburger {
                background: none;
                border: none;
                color: white;
                font-size: 24px;
                cursor: pointer;
            }

            .mobile-menu {
                display: none;
                background-color: #1a202c;
                position: absolute;
                top: 60px;
                left: 0;
                width: 100%;
                text-align: center;
            }

            .mobile-menu a {
                display: block;
                padding: 12px;
                color: white;
                border-bottom: 1px solid #4a5568;
            }
        }
    </style>
</head>
<body>

    <!-- Mobile Navbar (Shows only on smaller screens) -->
    <div class="mobile-nav d-lg-none">
        <button class="hamburger" id="hamburgerBtn">☰</button>
        <span class="text-white font-bold">File Retrieval System</span>
    </div>
    
    <div class="mobile-menu d-lg-none" id="mobileMenu">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <a href="{{ route('admin.register') }}">Admins</a>
        <a href="{{ route('admin.schedules.index') }}">Schedules</a>
        <a href="{{ route('admin.reports.index') }}">Reports</a>
        <a href="{{ route('admin.students.index') }}">Students</a>
        <a href="{{ route('admin.files.index') }}">Files</a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="text-white border-0 bg-transparent w-100 text-start p-3">
                Logout
            </button>
        </form>
    </div>

    <!-- Desktop Navbar (Top Navigation for Large Screens) -->
    <nav class="navbar navbar-expand-lg navbar-custom d-none d-lg-flex">
        <div class="container">
            <!-- Logo & Title -->
            <a href="{{ route('admin.dashboard') }}" class="nav-logo text-white">
                <img src="{{ asset('images/PRMSU.png') }}" alt="PRMSU Logo"> File-Retrieval System
            </a>

            <!-- Navigation Links -->
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.register') }}">Admins</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.schedules.index') }}">Schedules</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.reports.index') }}">Reports</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.students.index') }}">Students</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.files.index') }}">Files</a>
                </li>
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="nav-link border-0 bg-transparent text-white">
                            Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content Area -->
    <div class="content-area">
        <main class="p-4">
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Mobile Menu Toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenu = document.getElementById('mobileMenu');
            const hamburgerBtn = document.getElementById('hamburgerBtn');

            hamburgerBtn.addEventListener('click', function() {
                mobileMenu.style.display = mobileMenu.style.display === 'block' ? 'none' : 'block';
            });

            document.addEventListener('click', function(event) {
                if (!mobileMenu.contains(event.target) && event.target !== hamburgerBtn) {
                    mobileMenu.style.display = 'none';
                }
            });
        });

        // Auto Logout After Inactivity
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

        resetLogoutTimer(); // Start timer when page loads
    </script>

    @stack('scripts')
</body>
</html>
