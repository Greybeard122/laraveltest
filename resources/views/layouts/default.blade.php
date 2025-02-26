<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'File Retrieval System' }}</title>

    @if (app()->environment('production'))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <link rel="icon" href="{{ asset('images/PRMSU.png') }}">
    
    <!-- External Libraries -->
    <script src="https://unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Sidebar Styling */
        .sidebar {
            width: 250px;
            background-color: #2d3748;
            position: fixed;
            height: 100vh;
            left: -250px;
            top: 0;
            transition: left 0.3s ease-in-out;
            padding-top: 15px;
            color: white;
        }

        .sidebar.active {
            left: 0;
        }

        .sidebar .nav-link {
            color: white;
            padding: 10px 15px;
            display: block;
            transition: background 0.3s;
        }

        .sidebar .nav-link:hover {
            background-color: #4a5568;
        }

        /* Content Area */
        .content-area {
            transition: margin-left 0.3s ease-in-out;
        }

        /* Adjust content when sidebar is open */
        .sidebar.active + .content-area {
            margin-left: 250px;
        }

        /* Top Navigation */
        .navbar-custom {
            background-color: #2d3748;
            color: white;
            padding: 12px;
        }

        .navbar-custom .nav-link {
            color: white;
            padding: 8px 16px;
            transition: background 0.3s;
        }

        .navbar-custom .nav-link:hover {
            background-color: #4a5568;
            border-radius: 5px;
        }

        /* Mobile Sidebar Button */
        .hamburger-btn {
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
        }

        /* Close Sidebar Button */
        .close-sidebar {
            position: absolute;
            right: 15px;
            top: 10px;
            background: none;
            border: none;
            font-size: 24px;
            color: white;
            cursor: pointer;
        }

        /* Mobile Adjustments */
        @media (max-width: 768px) {
            .sidebar {
                left: -250px;
                width: 100%;
            }

            .sidebar.active {
                left: 0;
            }

            .content-area {
                margin-left: 0;
            }
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-800 bg-cover bg-center" style="background-image: url('{{ secure_asset('images/bg-login-register.jpg') }}');">
    <x-messages/>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <button class="close-sidebar" id="closeSidebarBtn">×</button>
        
        <!-- Sidebar Links -->
        <ul class="nav flex-column">
            <li><a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li><a class="nav-link" href="{{ route('admin.register') }}">Admins</a></li>
            <li><a class="nav-link" href="{{ route('admin.schedules.index') }}">Schedules</a></li>
            <li><a class="nav-link" href="{{ route('admin.reports.index') }}">Reports</a></li>
            <li><a class="nav-link" href="{{ route('admin.students.index') }}">Students</a></li>
            <li><a class="nav-link" href="{{ route('admin.files.index') }}">Files</a></li>
            <li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-link border-0 bg-transparent text-white w-100 text-start">
                        Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>

    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <!-- Hamburger Button -->
            <button class="hamburger-btn" id="hamburgerBtn">☰</button>

            <!-- Page Title -->
            <div class="text-white mx-auto">
                File-Retrieval System
            </div>

            <div class="d-none d-lg-block" style="width: 48px;"></div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="content-area p-4">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Sidebar Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const contentArea = document.querySelector('.content-area');
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

        // Auto Logout After Inactivity
        let logoutTimer;

        function resetLogoutTimer() {
            clearTimeout(logoutTimer);
            logoutTimer = setTimeout(() => {
                alert('You have been logged out due to inactivity.');

                let form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('logout') }}";

                let csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = "{{ csrf_token() }}";
                form.appendChild(csrfToken);

                document.body.appendChild(form);
                form.submit();
            }, 5 * 60 * 1000); // 5 minutes
        }

        document.addEventListener("mousemove", resetLogoutTimer);
        document.addEventListener("keypress", resetLogoutTimer);
        document.addEventListener("click", resetLogoutTimer);
        document.addEventListener("scroll", resetLogoutTimer);

        resetLogoutTimer();
    </script>

</body>
</html>
