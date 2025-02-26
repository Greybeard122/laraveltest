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
    <script src="https://unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-100 text-gray-800 bg-cover bg-center" style="background-image: url('{{ secure_asset('images/bg-login-register.jpg') }}');">
    <x-nav/>
    <x-messages/>

    <div class="flex flex-col min-h-screen">
        <!-- Main Content Section -->
        <main class="flex-grow max-w-screen-lg mx-auto p-6 @yield('main-class', 'min-h-[30vh]') mt-20">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-6 shadow-inner">
            <div class="container mx-auto text-center">
                <p>&copy; {{ date('Y') }} PRMSU File Retrieval System | All Rights Reserved</p>
            </div>
        </footer>
    </div>
</body>
<script>
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

</html>
