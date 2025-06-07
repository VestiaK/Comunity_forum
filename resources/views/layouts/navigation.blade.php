<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<!-- Head: Flowbite CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const themeToggleBtn = document.getElementById('theme-toggle');
    const darkIcon = document.getElementById('theme-toggle-dark-icon');
    const lightIcon = document.getElementById('theme-toggle-light-icon');

    // Set icon on load
    if (localStorage.getItem('color-theme') === 'dark' ||
        (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
        lightIcon.classList.remove('hidden');
    } else {
        document.documentElement.classList.remove('dark');
        darkIcon.classList.remove('hidden');
    }

    themeToggleBtn.addEventListener('click', function() {
        darkIcon.classList.toggle('hidden');
        lightIcon.classList.toggle('hidden');
        if (document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('color-theme', 'light');
        } else {
            document.documentElement.classList.add('dark');
            localStorage.setItem('color-theme', 'dark');
        }
    });
});
</script>
<nav class="bg-white border-b border-gray-200 dark:bg-gray-900 dark:border-gray-800 shadow-sm sticky top-0 z-50">
  <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
    <a href="/dashboard" class="flex items-center space-x-3 rtl:space-x-reverse">
      <img src="{{ asset('images/logo.png') }}" class="h-10" alt="Forum Logo" />
      <span class="absolute pl-8 text-2xl font-bold whitespace-nowrap text-purple-700 dark:text-white">Codexium</span>
    </a>
    <div class="flex items-center space-x-2 md:order-2">
      <!-- Search Bar -->
      {{-- <div class="relative hidden md:block">
        <input type="text" id="search-navbar" class="block w-64 p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500" placeholder="Cari diskusi...">
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
          <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/></svg>
        </div>
      </div> --}}
      @auth
      <button id="dropdownUserAvatarButton" data-dropdown-toggle="dropdownAvatar" class="flex text-sm bg-gray-800 rounded-full md:me-0 focus:ring-4 focus:ring-purple-300 dark:focus:ring-purple-600" type="button">
        <span class="sr-only">Open user menu</span>
        <img class="w-8 h-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=3b82f6&color=fff" alt="user photo">
      </button>
      <div id="dropdownAvatar" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700 dark:divide-gray-600">
        <div class="px-4 py-3 text-sm text-gray-900 dark:text-white">
          <div>{{auth()->user()->name}}</div>
          <div class="font-medium truncate">{{auth()->user()->email}}</div>
        </div>
        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownUserAvatarButton">
          <li><a href="/profile" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Profile</a></li>
          <li><a href="/reputation" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Reputasi</a></li>
          <!-- Tombol Toggle Dark Mode -->

<div class="flex justify-center">
    <button id="theme-toggle" type="button" class="p-2 rounded focus:outline-none text-gray-700 dark:text-gray-200">

        <svg id="theme-toggle-light-icon" class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5V3m0 18v-2M7.05 7.05 5.636 5.636m12.728 12.728L16.95 16.95M5 12H3m18 0h-2M7.05 16.95l-1.414 1.414M18.364 5.636 16.95 7.05M16 12a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z"/>
        </svg>

        <svg id="theme-toggle-dark-icon" class="w-6 h-6 text-gray-800 dark:text-white hidden" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9 9 0 0 1-.5-17.986V3c-.354.966-.5 1.911-.5 3a9 9 0 0 0 9 9c.239 0 .254.018.488 0A9.004 9.004 0 0 1 12 21Z"/>
      </svg>    
    </button>
</div>

        </ul>
        <form class="py-2" action="/logout" method="POST">
          @csrf
          <button type="submit" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Log Out</button>
        </form>
      </div>
      @else
      <div class="flex space-x-2">
        <a href="/login" class="text-sm text-gray-700 dark:text-gray-300 hover:underline px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">Login</a>
        <a href="/register" class="text-sm text-white bg-purple-600 hover:bg-purple-700 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg px-4 py-2 dark:bg-purple-500 dark:hover:bg-purple-600 dark:focus:ring-purple-800">Sign Up</a>
      </div>
      @endauth
      <!-- Hamburger -->
      <button data-collapse-toggle="navbar-search" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-search" aria-expanded="false">
        <span class="sr-only">Open main menu</span>
        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/></svg>
      </button>
    </div>
    <!-- Navigasi -->
    <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-search">
      <ul class="flex flex-col p-4 md:p-0 mt-4 font-medium border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
        <li>
          <a href="/dashboard" class="block py-2 px-3 {{ request()->is('/dashboard') ? 'text-purple-700' : 'text-gray-900 dark:text-white'  }} rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-purple-700 md:p-0 md:dark:hover:text-purple-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent" aria-current="page">Home</a>
        </li>
        <li>
          <a href="/posts" class="block py-2 px-3 {{ request()->is('posts') ? 'text-purple-700' : 'text-gray-900 dark:text-white' }} rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-purple-700 md:p-0 md:dark:hover:text-purple-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Diskusi</a>
        </li>
        <li class="relative group">
          <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown-kategori" type="button"
            class="flex items-center py-2 px-3 text-gray-900 dark:text-white rounded-sm hover:bg-gray-100 dark:hover:bg-gray-700 md:hover:bg-transparent md:p-0 md:dark:hover:text-purple-400 transition-colors">
            Kategori
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
          </button>
          <div id="dropdown-kategori" class="hidden absolute left-0 z-20 bg-white dark:bg-gray-800 rounded shadow-lg mt-2 min-w-[180px] border border-gray-100 dark:border-gray-700">
            @foreach($categories as $cat)
              <a href="/posts?category={{ $cat->slug }}"
                class="block px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-purple-100 dark:hover:bg-purple-700 transition-colors">
                {{ $cat->name }}
              </a>
            @endforeach
          </div>
        </li>
      </ul>
    </div>
  </div>
</nav>

