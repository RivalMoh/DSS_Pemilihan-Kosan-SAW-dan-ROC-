<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Admin Panel') - {{ config('app.name') }}</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @stack('styles')
    
    <style>
        [x-cloak] { display: none !important; }
        .sidebar-transition {
            transition: all 0.3s ease-in-out;
        }
        .sidebar-width {
            width: 250px;
        }
        .sidebar-collapsed {
            margin-left: -250px;
        }
        .content-expanded {
            margin-left: 0;
            width: 100%;
        }
    </style>
</head>
<body class="h-full" x-data="{ sidebarOpen: window.innerWidth >= 1024, mobileMenuOpen: false }">
    <div class="min-h-screen flex">
        <!-- Mobile menu button -->
        <div class="lg:hidden fixed bottom-4 right-4 z-50">
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-3 rounded-full bg-indigo-600 text-white shadow-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i x-show="!mobileMenuOpen" class="fas fa-bars"></i>
                <i x-show="mobileMenuOpen" class="fas fa-times"></i>
            </button>
        </div>

        <!-- Sidebar -->
        <div class="hidden lg:flex lg:flex-shrink-0">
            <div class="flex flex-col w-64 border-r border-gray-200 bg-white">
                <!-- Logo -->
                <div class="h-16 flex items-center px-4 border-b border-gray-200">
                    <div class="flex items-center">
                        <i class="fas fa-home text-indigo-600 text-2xl mr-2"></i>
                        <span class="text-xl font-bold text-gray-900">{{ config('app.name') }}</span>
                        <span class="ml-2 px-2 py-0.5 text-xs font-medium bg-indigo-100 text-indigo-800 rounded-full">Admin</span>
                    </div>
                </div>
                
                <!-- Navigation -->
                <nav class="flex-1 flex flex-col overflow-y-auto">
                    <div class="px-3 py-4 space-y-1">
                        <a href="/admin/dashboard" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->is('admin/dashboard*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="fas fa-tachometer-alt mr-3 text-gray-400 group-hover:text-gray-500 {{ request()->is('admin/dashboard*') ? 'text-indigo-500' : '' }}"></i>
                            Dashboard
                        </a>
                        
                        <div x-data="{ open: {{ request()->is('admin/settings/*') ? 'true' : 'false' }} }">
                            <button @click="open = !open" class="w-full group flex items-center justify-between px-3 py-2 text-sm font-medium rounded-md text-gray-700 hover:bg-gray-50 hover:text-gray-900">
                                <div class="flex items-center">
                                    <i class="fas fa-cog mr-3 text-gray-400 group-hover:text-gray-500"></i>
                                    Pengaturan
                                </div>
                                <i class="fas fa-chevron-down text-xs text-gray-400 transform transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                            </button>
                            <div x-show="open" class="mt-1 space-y-1 pl-11">
                                <a href="/admin/settings/weights" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->is('admin/settings/weights*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    <i class="fas fa-balance-scale mr-3 text-gray-400"></i>
                                    Bobot Kriteria
                                </a>
                                <a href="/admin/settings/attribute-ranges" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->is('admin/settings/attribute-ranges*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    <i class="fas fa-ruler-combined mr-3 text-gray-400"></i>
                                    Rentang Atribut
                                </a>
                            </div>
                        </div>
                        
                        <a href="#" class="group flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-50 hover:text-gray-900">
                            <i class="fas fa-users mr-3 text-gray-400 group-hover:text-gray-500"></i>
                            Pengguna
                        </a>
                    </div>
                </nav>
                
                <!-- User profile dropdown -->
                <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
                    <div class="flex items-center">
                        <div class="h-9 w-9 rounded-full bg-indigo-100 flex items-center justify-center">
                            <i class="fas fa-user text-indigo-600"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</p>
                            <a href="{{ route('logout') }}" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                               class="text-xs font-medium text-gray-500 hover:text-indigo-600">
                                Keluar
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile sidebar -->
        <div x-show="mobileMenuOpen" 
             @click.away="mobileMenuOpen = false"
             x-transition:enter="transition-transform duration-300 ease-in-out"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition-transform duration-300 ease-in-out"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="lg:hidden fixed inset-0 z-40">
            <div class="fixed inset-0 bg-gray-600 bg-opacity-75" @click="mobileMenuOpen = false"></div>
            <div class="relative flex-1 flex flex-col w-64 max-w-xs bg-white h-full">
                <div class="h-16 flex items-center px-4 border-b border-gray-200">
                    <div class="flex items-center">
                        <i class="fas fa-home text-indigo-600 text-2xl mr-2"></i>
                        <span class="text-xl font-bold text-gray-900">{{ config('app.name') }}</span>
                        <span class="ml-2 px-2 py-0.5 text-xs font-medium bg-indigo-100 text-indigo-800 rounded-full">Admin</span>
                    </div>
                </div>
                <div class="flex-1 flex flex-col overflow-y-auto">
                    <nav class="px-3 py-4 space-y-1">
                        <a href="/admin/dashboard" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->is('admin/dashboard*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}" @click="mobileMenuOpen = false">
                            <i class="fas fa-tachometer-alt mr-3 text-gray-400 group-hover:text-gray-500 {{ request()->is('admin/dashboard*') ? 'text-indigo-500' : '' }}"></i>
                            Dashboard
                        </a>
                        
                        <div x-data="{ open: {{ request()->is('admin/settings/*') ? 'true' : 'false' }} }">
                            <button @click="open = !open" class="w-full group flex items-center justify-between px-3 py-2 text-sm font-medium rounded-md text-gray-700 hover:bg-gray-50 hover:text-gray-900">
                                <div class="flex items-center">
                                    <i class="fas fa-cog mr-3 text-gray-400 group-hover:text-gray-500"></i>
                                    Pengaturan
                                </div>
                                <i class="fas fa-chevron-down text-xs text-gray-400 transform transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                            </button>
                            <div x-show="open" class="mt-1 space-y-1 pl-11">
                                <a href="/admin/settings/weights" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->is('admin/settings/weights*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}" @click="mobileMenuOpen = false">
                                    <i class="fas fa-balance-scale mr-3 text-gray-400"></i>
                                    Bobot Kriteria
                                </a>
                                <a href="/admin/settings/attribute-ranges" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->is('admin/settings/attribute-ranges*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}" @click="mobileMenuOpen = false">
                                    <i class="fas fa-ruler-combined mr-3 text-gray-400"></i>
                                    Rentang Atribut
                                </a>
                            </div>
                        </div>
                        
                        <a href="#" class="group flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-50 hover:text-gray-900" @click="mobileMenuOpen = false">
                            <i class="fas fa-users mr-3 text-gray-400 group-hover:text-gray-500"></i>
                            Pengguna
                        </a>
                    </nav>
                </div>
                <div class="flex-shrink-0 border-t border-gray-200 p-4">
                    <div class="flex items-center">
                        <div class="h-9 w-9 rounded-full bg-indigo-100 flex items-center justify-center">
                            <i class="fas fa-user text-indigo-600"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</p>
                            <a href="{{ route('logout') }}" 
                               onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();"
                               class="text-xs font-medium text-gray-500 hover:text-indigo-600">
                                Keluar
                            </a>
                            <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="hidden">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top navigation -->
            <header class="bg-white shadow-sm z-10">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                    <div class="flex items-center justify-between">
                        <h1 class="text-2xl font-semibold text-gray-900">@yield('title', 'Dashboard')</h1>
                        <div class="flex items-center space-x-4">
                            <button class="p-2 text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 rounded-md">
                                <i class="far fa-bell"></i>
                            </button>
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                                    <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <span class="hidden md:inline text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                                    <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                    <div class="py-1" role="menu" aria-orientation="vertical">
                                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                            <i class="fas fa-user mr-2"></i> Profil
                                        </a>
                                        <a href="{{ route('logout') }}" 
                                           onclick="event.preventDefault(); document.getElementById('logout-form-dropdown').submit();"
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" 
                                           role="menuitem">
                                            <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                                        </a>
                                        <form id="logout-form-dropdown" action="{{ route('logout') }}" method="POST" class="hidden">
                                            @csrf
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="flex-1 overflow-y-auto bg-gray-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    @include('layouts.partials.alerts')
                    
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
    
    <script>
        // Close mobile menu when clicking on a link
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuLinks = document.querySelectorAll('.mobile-menu-link');
            mobileMenuLinks.forEach(link => {
                link.addEventListener('click', function() {
                    const mobileMenu = document.querySelector('[x-data]');
                    if (mobileMenu) {
                        mobileMenu.__x.$data.mobileMenuOpen = false;
                    }
                });
            });

            // Close mobile menu when window is resized to desktop
            function handleResize() {
                if (window.innerWidth >= 1024) {
                    const mobileMenu = document.querySelector('[x-data]');
                    if (mobileMenu) {
                        mobileMenu.__x.$data.mobileMenuOpen = false;
                    }
                }
            }

            window.addEventListener('resize', handleResize);
        });
    </script>
</body>
</html>
