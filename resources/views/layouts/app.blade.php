{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Sidebar -->
        <aside id="sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0">
            <div class="h-full px-3 py-4 overflow-y-auto bg-white border-r border-gray-200">
                <!-- Logo/Brand -->
                <div class="mb-6 px-3">
                    <h1 class="text-xl font-bold text-gray-800">Perpustakaan</h1>
                    <p class="text-sm text-gray-500">{{ auth()->user()->role }}</p>
                </div>

                <!-- Navigation Menu -->
                <ul class="space-y-2 font-medium">
                    <!-- Dashboard -->
                    <li>
                        <a href="{{ route('dashboard') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-900' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span class="ml-3">Dashboard</span>
                        </a>
                    </li>

                    @if(auth()->user()->isPeminjam())
                        <!-- Menu untuk Peminjam -->
                        <li>
                            <a href="{{ route('koleksi.buku') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('koleksi.buku') ? 'bg-blue-50 text-blue-600' : 'text-gray-900' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                <span class="ml-3">Koleksi Buku</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('peminjaman-user.index') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('peminjaman-user.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-900' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <span class="ml-3">Peminjaman Saya</span>
                            </a>
                        </li>
                    @endif

                    @if(auth()->user()->isAdministrator() || auth()->user()->isPetugas())
                        <!-- Menu untuk Administrator & Petugas -->
                        <li>
                            <a href="{{ route('buku.index') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('buku.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-900' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                <span class="ml-3">Manajemen Buku</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('peminjaman.index') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('peminjaman.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-900' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                </svg>
                                <span class="ml-3">Data Peminjaman</span>
                            </a>
                        </li>
                        @if(auth()->user()->isAdministrator() || auth()->user()->isPetugas())
                        <li>
                            <a href="{{ route('laporan.index') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('laporan.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-900' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span class="ml-3">Laporan</span>
                            </a>
                        </li>
                        @endif
                    @endif

                    @if(auth()->user()->isAdministrator())
                        <!-- Menu khusus Administrator -->
                        <li>
                            <a href="{{ route('users.index') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('users.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-900' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                <span class="ml-3">Manajemen User</span>
                            </a>
                        </li>
                    @endif

                    <!-- Divider -->
                    <li class="pt-4 mt-4 border-t border-gray-200">
                        <a href="{{ route('profile.edit') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('profile.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-900' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span class="ml-3">Profile</span>
                        </a>
                    </li>

                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                <span class="ml-3">Logout</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="sm:ml-64">
            <!-- Top Navigation Bar -->
            <nav class="bg-white border-b border-gray-200">
                <div class="px-3 py-3 lg:px-5 lg:pl-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center justify-start">
                            <button data-drawer-target="sidebar" data-drawer-toggle="sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                                </svg>
                            </button>
                            @if (isset($header))
                                <div class="ml-4">
                                    {{ $header }}
                                </div>
                            @endif
                        </div>
                        <div class="flex items-center">
                            <div class="text-sm text-gray-700">
                                <span class="font-medium">{{ auth()->user()->name }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="p-4">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Toggle sidebar for mobile -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleButton = document.querySelector('[data-drawer-toggle="sidebar"]');
        
        if (toggleButton) {
            toggleButton.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
            });
        }
    </script>
</body>
</html>