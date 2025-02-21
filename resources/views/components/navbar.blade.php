<div x-data="{ open: false }" class="bg-gray-900 fixed w-full z-20 top-0 left-0 px-6 py-4 text-white shadow-lg">
    <div class="container flex justify-between items-center mx-auto">
        <!-- Logo -->
        <a href="/" class="flex items-center hover:text-sky-400 transition-transform transform hover:scale-105">
            <img src="{{ asset('images/PRMSU.png') }}" alt="Logo" class="h-10 mr-2">
            <span class="text-lg font-semibold">File-Retrieval System</span>
        </a>

        <!-- Mobile Menu Button -->
        <button @click="open = !open" class="md:hidden focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" width="24px" fill="#e8eaed">
                <path d="M3 6h18M3 12h18m-18 6h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </button>

        <!-- Menu Items (This part will be handled by the x-items component) -->
        <x-items :class="'md:hidden hover:text-sky-400 hover:border-2 hover:border-sky-400 transition duration-300 ease-in-out hover:p-2 hover:rounded-lg'" />
    </div>
</div>
