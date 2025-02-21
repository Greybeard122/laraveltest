<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to PRMSU SM File Retrieval</title>
    @vite('resources/css/app.css') <!-- Tailwind CSS -->
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gradient-to-r from-blue-600 to-indigo-900 min-h-screen">

    <!-- Navbar -->
    <nav x-data="{ open: false }" class="bg-gray-900 fixed w-full z-20 top-0 left-0 border-gray-700 px-4 sm:px-6 py-3 text-white shadow-lg">
        <div class="container flex flex-wrap justify-between items-center mx-auto">
            <!-- Logo with Tooltip -->
            <div class="relative group">
                <a href="/" class="flex items-center transition duration-300 ease-in-out hover:text-sky-400 hover:scale-105">
                    <img src="{{ asset('images/PRMSU.png') }}" alt="Logo" class="h-10 mr-3">
                    <span class="text-xl font-semibold whitespace-nowrap">
                        File-Retrieval System
                    </span>
                </a>
                <div class="absolute left-0 mt-2 w-max opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-gray-700 text-white text-sm rounded-md px-3 py-1">
                    Return to Homepage
                </div>
            </div>

            <!-- Mobile Menu Button -->
            <button @click="open = !open" class="inline-flex items-center p-2 text-white rounded-lg focus:outline-none md:hidden hover:bg-gray-700 hover:border-2 hover:border-sky-400 transition duration-300 ease-in-out">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                    <path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z" />
                </svg>
            </button>

            <!-- Menu Items -->
            <div :class="open ? 'block' : 'hidden'" class="w-full md:flex md:w-auto" id="navbar-main">
                <ul class="flex flex-col md:flex-row md:space-x-6 px-4">
                    <li>
                        <a href="{{ route('login') }}" class="block py-2 px-4 text-white rounded-lg transition duration-300 hover:bg-gray-700 hover:text-sky-400">
                            Login
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('register') }}" class="block py-2 px-4 text-white rounded-lg transition duration-300 hover:bg-gray-700 hover:text-sky-400">
                            Register
                        </a>
                    </li>
                    
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="h-screen bg-gradient-to-r from-blue-600 to-indigo-900 flex items-center justify-center text-white">
        <div class="text-center px-6 md:px-12">
            <h1 class="text-5xl font-extrabold leading-tight mb-6">Welcome to the PRMSU San Marcelino File-Retrieval System</h1>
            <p class="text-xl mb-6">A platform for managing and retrieving student files efficiently.</p>
            <a href="{{ route('login') }}" class="btn btn-light btn-lg">
                Get Started
            </a>
        </div>
    </div>

    <!-- Key Features Section -->
    <div class="h-screen bg-gray-100 py-12 flex items-center">
        <div class="container mx-auto text-center">
            <h2 class="text-4xl font-bold text-sky-400 mb-6">Key Features</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 px-4">
                <!-- Feature 1 -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-sky-400 mb-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path d="M3 5h14M3 12h14M3 19h14" />
                    </svg>
                    <h3 class="text-2xl font-semibold mb-4">Scheduling</h3>
                    <p class="text-gray-600">Easily schedule document retrieval and management tasks with automated reminders.</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-sky-400 mb-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path d="M3 5h14M3 12h14M3 19h14" />
                    </svg>
                    <h3 class="text-2xl font-semibold mb-4">Organized and User-Friendly</h3>
                    <p class="text-gray-600">Our system ensures all your documents are well-organized, with a simple and easy-to-use interface.</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-sky-400 mb-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path d="M3 5h14M3 12h14M3 19h14" />
                    </svg>
                    <h3 class="text-2xl font-semibold mb-4">Secure and Reliable</h3>
                    <p class="text-gray-600">Rest assured that your documents are stored securely with industry-standard encryption and reliability.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-8">
        <div class="container mx-auto text-center">
            <p>&copy; {{ date('Y') }} Thesis File Retrieval System | All Rights Reserved</p>
        </div>
    </footer>

    @vite('resources/js/app.js')
</body>
</html>
