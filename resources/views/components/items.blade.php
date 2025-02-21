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
                <li>
                    <a href="{{ route('admin.schedules.index') }}" class="block py-2 px-4 text-white rounded-lg transition duration-300 hover:bg-gray-700 hover:text-sky-400">
                        Manage Schedules
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.register') }}" class="block py-2 px-4 text-white rounded-lg transition duration-300 hover:bg-gray-700 hover:text-sky-400">
                        Register Admin
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.files.index') }}" class="block py-2 px-4 text-white rounded-lg transition duration-300 hover:bg-gray-700 hover:text-sky-400">
                        Manage Files
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.reports.index') }}" class="block py-2 px-4 text-white rounded-lg transition duration-300 hover:bg-gray-700 hover:text-sky-400">
                        View Reports
                    </a>
                </li>
            @endif
            
            <li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="block py-2 px-4 text-white rounded-lg transition duration-300 hover:bg-gray-700 hover:text-sky-400">
                        Logout
                    </button>
                </form>
            </li>
        @endauth
    </ul>
</div>