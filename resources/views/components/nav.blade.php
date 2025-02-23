<div x-data="{ open: false }" class="bg-gray-800 fixed w-full z-20 top-0 left-0 px-6 py-4 text-white shadow-lg">
    <div class="container flex justify-between items-center mx-auto">
        <!-- Logo and Title -->
        <a href="/" class="flex items-center justify-center md:justify-start hover:text-sky-400 transition-transform transform hover:scale-105">
            <img src="{{ asset('images/PRMSU.png') }}" alt="Logo" class="h-10 mr-2">
            <span class="text-lg font-semibold">File-Retrieval System</span>
        </a>

        <!-- Mobile Menu Button -->
        <button @click="open = !open" class="md:hidden focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" width="24px" fill="#e8eaed">
                <path d="M3 6h18M3 12h18m-18 6h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </button>

        <!-- Menu Items -->
        <div :class="open ? 'block' : 'hidden'" class="w-full md:flex md:w-auto" id="navbar-main">
            <ul class="flex flex-col md:flex-row md:space-x-6 px-4">
                @csrf

                <!-- Links for Guests -->
                @guest
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
                @endguest

                <!-- Links for Authenticated Users -->
                @auth
                    @if(auth()->user()->is_admin == 1)
                        <!-- Admin Links -->
                        <li>
                            <a href="{{ route('admin.register') }}" class="block py-2 px-4 text-white rounded-lg transition duration-300 hover:bg-gray-700 hover:text-sky-400">
                                Add Admin
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.schedules.index') }}" class="block py-2 px-4 text-white rounded-lg transition duration-300 hover:bg-gray-700 hover:text-sky-400">
                                Manage Requests
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.reports.index') }}" class="block py-2 px-4 text-white rounded-lg transition duration-300 hover:bg-gray-700 hover:text-sky-400">
                                View Reports
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.students.index')}}" class="block py-2 px-4 text-white rounded-lg transition duration-300 hover:bg-gray-700 hover:text-sky-400"}}">
                                Manage Students
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.files.index') }}" class="block py-2 px-4 text-white rounded-lg transition duration-300 hover:bg-gray-700 hover:text-sky-400">
                                Manage Files
                            </a>
                        </li>
                        
                    @elseif(auth()->user()->is_admin == 0)
                        <!-- Student Links -->
                        <li>
                            <a href="{{ route('student.dashboard') }}" class="block py-2 px-4 text-white rounded-lg transition duration-300 hover:bg-gray-700 hover:text-sky-400">
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('student.schedules.create') }}" class="block py-2 px-4 text-white rounded-lg transition duration-300 hover:bg-gray-700 hover:text-sky-400">
                                Create Schedule
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('student.profile.show') }}" 
   class="block py-2 px-4 text-white rounded-lg transition duration-300 hover:bg-gray-700 hover:text-sky-400"
   onclick="event.preventDefault(); window.location.href='{{ route('student.profile.show') }}';">
    My Profile
</a>
                        </li>
                    @endif

                    <!-- Logout -->
                    <li>
                        <form action="{{ route('logout') }}" method="POST" class="logout-form">
                            @csrf
                            <button type="submit" class="nav-button">
                                Logout
                            </button>
                        </form>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</div>
