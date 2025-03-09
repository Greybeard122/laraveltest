<div :class="open ? 'block' : 'hidden'" class="w-full md:flex md:w-auto" id="navbar-main">
    <ul class="flex flex-col md:flex-row md:space-x-6 px-4">
        @guest
            <li class="hover:text-sky-400 transition-transform transform hover:scale-105">
                <a href="{{ route('login') }}" class="block py-2 px-4 text-white rounded-lg transition duration-300 hover:bg-gray-700 hover:text-sky-400">
                    Login
                </a>
            </li>
            <li class="hover:text-sky-400 transition-transform transform hover:scale-105">
                <a href="{{ route('register') }}" class="block py-2 px-4 text-white rounded-lg transition duration-300 hover:bg-gray-700 hover:text-sky-400">
                    Register
                </a>
            </li>
            <li class="hover:text-sky-400 transition-transform transform hover:scale-105">
                <a href="{{ route('student.password.request') }}" class="block py-2 px-4 text-white rounded-lg transition duration-300 hover:bg-gray-700 hover:text-sky-400">
                    Reset Password
                </a>
            </li>
        @endguest

        @auth('web')
            @if(auth()->user()->is_admin == 1)
                <li class="hover:text-sky-400 transition-transform transform hover:scale-105">
                    <a href="{{ route('admin.register') }}" class="block py-2 px-4 text-white rounded-lg transition duration-300 hover:bg-gray-700 hover:text-sky-400">
                        Admins
                    </a>
                </li>
                <li class="hover:text-sky-400 transition-transform transform hover:scale-105">
                    <a href="{{ route('admin.schedules.index') }}" class="block py-2 px-4 text-white rounded-lg transition duration-300 hover:bg-gray-700 hover:text-sky-400">
                        Requests
                    </a>
                </li>
                <li class="hover:text-sky-400 transition-transform transform hover:scale-105">
                    <a href="{{ route('admin.reports.index') }}" class="block py-2 px-4 text-white rounded-lg transition duration-300 hover:bg-gray-700 hover:text-sky-400">
                        Reports
                    </a>
                </li>
                <li class="hover:text-sky-400 transition-transform transform hover:scale-105">
                    <a href="{{ route('admin.students.index')}}" class="block py-2 px-4 text-white rounded-lg transition duration-300 hover:bg-gray-700 hover:text-sky-400">
                        Students
                    </a>
                </li>
                <li class="hover:text-sky-400 transition-transform transform hover:scale-105">
                    <a href="{{ route('admin.files.index') }}" class="block py-2 px-4 text-white rounded-lg transition duration-300 hover:bg-gray-700 hover:text-sky-400">
                        Files
                    </a>
                </li>
                <li class="hover:text-sky-400 transition-transform transform hover:scale-105">
                    <a href="{{ route('admin.school-years-semesters.index') }}" class="block py-2 px-4 text-white rounded-lg transition duration-300 hover:bg-gray-700 hover:text-sky-400">
                        Terms
                    </a>
                </li>                        
            @endif
        @endauth

        @auth('student')
            <li class="hover:text-sky-400 transition-transform transform hover:scale-105">
                <a href="{{ route('student.dashboard') }}" class="block py-2 px-4 text-white rounded-lg transition duration-300 hover:bg-gray-700 hover:text-sky-400">
                    Dashboard
                </a>
            </li>
            <li class="hover:text-sky-400 transition-transform transform hover:scale-105">
                <a href="{{ route('student.schedules.create') }}" class="block py-2 px-4 text-white rounded-lg transition duration-300 hover:bg-gray-700 hover:text-sky-400">
                    Create Schedule
                </a>
            </li>
            <li class="hover:text-sky-400 transition-transform transform hover:scale-105">
                <a href="{{ route('student.history') }}" class="block py-2 px-4 text-white rounded-lg transition duration-300 hover:bg-gray-700 hover:text-sky-400">
                    Request History
                </a>
            </li>
            
            <li class="hover:text-sky-400 transition-transform transform hover:scale-105">
                <a href="{{ route('student.profile.show') }}" class="block py-2 px-4 text-white rounded-lg transition duration-300 hover:bg-gray-700 hover:text-sky-400">
                    My Profile
                </a>
            </li>
        @endauth

        @if(Auth::guard('web')->check() || Auth::guard('student')->check())
            <li class="hover:text-sky-400 transition-transform transform hover:scale-105">
                <form action="{{ route('logout') }}" method="POST" class="block">
                    @csrf
                    <button type="submit" class="w-full text-left py-2 px-4 text-white rounded-lg transition duration-300 hover:bg-gray-700 hover:text-sky-400">
                        Logout
                    </button>
                </form>
            </li>
        @endif
    </ul>
</div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let currentUrl = window.location.href;

        // Apply active state only to top navbar links
        document.querySelectorAll(".navbar .nav-link").forEach(link => {
            if (link.href === currentUrl) {
                link.classList.add("active");
            }
        });
    });
</script>