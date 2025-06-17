<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Panel - {{ config('app.name') }}</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @stack('styles')
</head>
<body class="bg-light">
    <div id="app">
        <!-- Sidebar -->
        <div class="d-flex" id="wrapper">
            <!-- Sidebar -->
            <div class="bg-dark border-right" id="sidebar-wrapper">
                <div class="sidebar-heading text-white p-3">
                    <h5 class="mb-0">Admin Panel</h5>
                    <small class="text-muted">{{ config('app.name') }}</small>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action bg-dark text-white {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                    </a>
                    
                    <div class="dropdown">
                        <a class="list-group-item list-group-item-action bg-dark text-white dropdown-toggle" 
                           href="#" role="button" id="attributeDropdown" 
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-list-ul mr-2"></i> Atribut
                        </a>
                        <div class="dropdown-menu bg-dark" aria-labelledby="attributeDropdown">
                            <a class="dropdown-item text-white {{ request()->is('admin/settings/weights*') ? 'active' : '' }}" 
                               href="/admin/settings/weights">
                                <i class="fas fa-balance-scale mr-2"></i> Bobot Kriteria
                            </a>
                            <a class="dropdown-item text-white {{ request()->is('admin/settings/attribute-ranges*') ? 'active' : '' }}" 
                               href="/admin/settings/attribute-ranges">
                                <i class="fas fa-ruler-combined mr-2"></i> Rentang Atribut
                            </a>
                        </div>
                    </div>
                    
                    <a href="#" class="list-group-item list-group-item-action bg-dark text-white">
                        <i class="fas fa-users mr-2"></i> Pengguna
                    </a>
                    <a href="#" class="list-group-item list-group-item-action bg-dark text-white">
                        <i class="fas fa-cog mr-2"></i> Pengaturan
                    </a>
                </div>
            </div>
            <!-- /#sidebar-wrapper -->

            <!-- Page Content -->
            <div id="page-content-wrapper">
                <!-- Top Navigation -->
                <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
                    <button class="btn btn-link" id="menu-toggle">
                        <i class="fas fa-bars"></i>
                    </button>

                    <button class="navbar-toggler" type="button" data-toggle="collapse" 
                            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" 
                            aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" 
                                   role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-user-circle fa-lg"></i>
                                    {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-user mr-2"></i> Profil
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>

                <!-- Main Content -->
                <div class="container-fluid p-4">
                    @include('layouts.partials.alerts')
                    
                    @yield('content')
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /#page-content-wrapper -->
        </div>
        <!-- /#wrapper -->
    </div>

    @stack('scripts')
    
    <script>
        // Toggle sidebar
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menu-toggle');
            const wrapper = document.getElementById('wrapper');
            
            if (menuToggle) {
                menuToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    wrapper.classList.toggle('toggled');
                });
            }
            
            // Enable tooltips
            $('[data-toggle="tooltip"]').tooltip();
            
            // Enable popovers
            $('[data-toggle="popover"]').popover();
        });
    </script>
</body>
</html>
