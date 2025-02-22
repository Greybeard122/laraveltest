<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'File Retrieval System' }}</title>

    @if(app()->environment('production'))
    <link href="{{ secure_asset('build/assets/app.css') }}" rel="stylesheet">
    <script src="{{ secure_asset('build/assets/app.js') }}" defer></script>
@else
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
@endif

    <link href="{{ secure_asset('css/responsive-admin.css') }}" rel="stylesheet">
    <link rel="icon" href="{{ secure_asset('images/PRMSU.png') }}">
    <script src="//unpkg.com/alpinejs" defer></script>
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

</html>
