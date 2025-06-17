@extends('admin.layouts.new')

@section('title', 'Dashboard Admin')

@push('styles')
<style>
    .stat-card {
        @apply bg-white rounded-xl shadow-sm border-l-4 p-6 transition-all duration-300 hover:shadow-md;
    }
    .stat-icon {
        @apply w-12 h-12 rounded-xl flex items-center justify-center text-2xl text-white;
    }
    .progress-thin {
        @apply h-1.5 rounded-full overflow-hidden bg-gray-100;
    }
    .progress-bar {
        @apply h-full bg-blue-500 rounded-full;
    }
    .badge {
        @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium;
    }
    .badge-success {
        @apply bg-green-100 text-green-800;
    }
    .badge-danger {
        @apply bg-red-100 text-red-800;
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-indigo-600 to-blue-600 rounded-2xl shadow-lg overflow-hidden">
        <div class="px-6 py-8 sm:p-10">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="text-white">
                    <h1 class="text-2xl md:text-3xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
                    <p class="text-indigo-100 max-w-2xl">Selamat datang di Dashboard Admin {{ config('app.name') }}. Kelola semua konten dan pengaturan dengan mudah dari satu tempat.</p>
                </div>
                <div class="mt-6 md:mt-0">
                    <div class="inline-flex items-center px-5 py-3 bg-white bg-opacity-10 backdrop-blur-sm rounded-xl border border-white border-opacity-20">
                        <div class="p-2.5 rounded-xl bg-white bg-opacity-20">
                            <i class="fas fa-home text-white text-xl"></i>
                        </div>
                        <div class="ml-4 text-right">
                            <p class="text-sm font-medium text-indigo-100">Total Kosan</p>
                            <p class="text-2xl font-bold text-white">{{ $stats['total_kosan'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total Kosan -->
        <div class="stat-card border-indigo-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Kosan</p>
                    <h3 class="mt-1 text-2xl font-bold text-gray-900">{{ $stats['total_kosan'] ?? 0 }}</h3>
                    <div class="mt-2">
                        <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded-full">
                            <i class="fas fa-arrow-up mr-1"></i> 12% from last month
                        </span>
                    </div>
                </div>
                <div class="stat-icon bg-indigo-500">
                    <i class="fas fa-home"></i>
                </div>
            </div>
        </div>

        <!-- Kosan Tersedia -->
        <div class="stat-card border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Kosan Tersedia</p>
                    <h3 class="mt-1 text-2xl font-bold text-gray-900">{{ $stats['available_kosan'] ?? 0 }}</h3>
                    <div class="mt-2">
                        <div class="flex items-center">
                            <span class="text-xs font-medium text-green-600">
                                {{ $stats['available_kosan'] > 0 ? round(($stats['available_kosan'] / $stats['total_kosan']) * 100, 1) : 0 }}% available
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                            <div class="bg-green-500 h-1.5 rounded-full" style="width: {{ $stats['available_kosan'] > 0 ? ($stats['available_kosan'] / $stats['total_kosan']) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
                <div class="stat-icon bg-green-500">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>

        <!-- Total Pengguna -->
        <div class="stat-card border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Pengguna</p>
                    <h3 class="mt-1 text-2xl font-bold text-gray-900">{{ $stats['total_users'] ?? 0 }}</h3>
                    <div class="mt-2">
                        <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-1 rounded-full">
                            <i class="fas fa-user-plus mr-1"></i> 5 new today
                        </span>
                    </div>
                </div>
                <div class="stat-icon bg-blue-500">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>

        <!-- Total Kunjungan -->
        <div class="stat-card border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Kunjungan</p>
                    <h3 class="mt-1 text-2xl font-bold text-gray-900">{{ $stats['total_visits'] ?? 0 }}</h3>
                    <div class="mt-2">
                        <span class="text-xs font-medium text-yellow-600 bg-yellow-50 px-2 py-1 rounded-full">
                            <i class="fas fa-chart-line mr-1"></i> 24% increase
                        </span>
                    </div>
                </div>
                <div class="stat-icon bg-yellow-500">
                    <i class="fas fa-eye"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Pengguna</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_users'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Admin</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_admins'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-shield fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Grid -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Weight Settings -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="px-6 py-5 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Pengaturan Bobot Kriteria</h3>
                    <a href="/admin/settings/weights" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    </a>
                </div>
            </div>
            <div class="p-6">
                <p class="text-sm text-gray-600 mb-4">Kelola bobot kriteria untuk sistem rekomendasi. Pastikan total bobot sama dengan 1.</p>
                <div class="space-y-4">
                    @php
                        $sampleWeights = [
                            ['name' => 'Harga', 'weight' => '0.2500', 'type' => 'cost'],
                            ['name' => 'Luas Kamar', 'weight' => '0.2000', 'type' => 'benefit'],
                            ['name' => 'Jarak Kampus', 'weight' => '0.1500', 'type' => 'cost'],
                            ['name' => 'Fasilitas', 'weight' => '0.1500', 'type' => 'benefit'],
                            ['name' => 'Keamanan', 'weight' => '0.1000', 'type' => 'benefit'],
                            ['name' => 'Kebersihan', 'weight' => '0.0750', 'type' => 'benefit'],
                            ['name' => 'Akses Lokasi', 'weight' => '0.0750', 'type' => 'benefit']
                        ];
                    @endphp
                    @foreach($sampleWeights as $weight)
                        <div class="group">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700 group-hover:text-indigo-600 transition-colors">{{ $weight['name'] }}</span>
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm font-mono text-gray-500 w-12 text-right">{{ $weight['weight'] }}</span>
                                    <span class="badge {{ $weight['type'] === 'benefit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} px-2.5 py-1 rounded-full text-xs">
                                        <i class="fas {{ $weight['type'] === 'benefit' ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1 text-xs"></i>
                                        {{ ucfirst($weight['type']) }}
                                    </span>
                                </div>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-blue-400 to-indigo-600 rounded-full transition-all duration-500 ease-out" style="width: {{ $weight['weight'] * 100 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Attribute Ranges -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 bg-gray-50 border-b">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Rentang Atribut</h3>
                    <a href="/admin/settings/attribute-ranges" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                        <i class="fas fa-ruler-combined mr-1.5"></i> Kelola
                    </a>
                </div>
            </div>
            <div class="p-6">
                <p class="text-sm text-gray-600 mb-4">Kelola rentang nilai untuk atribut numerik yang digunakan dalam rekomendasi.</p>
                <div class="space-y-5">
                    @php
                        $sampleRanges = [
                            ['name' => 'Harga Sewa', 'min' => '500000', 'max' => '5000000', 'unit' => '/bulan', 'icon' => 'fa-tag'],
                            ['name' => 'Luas Kamar', 'min' => '9', 'max' => '36', 'unit' => 'm²', 'icon' => 'fa-ruler-combined'],
                            ['name' => 'Jarak Kampus', 'min' => '0.1', 'max' => '5', 'unit' => 'km', 'icon' => 'fa-map-marker-alt']
                        ];
                    @endphp
                    @foreach($sampleRanges as $range)
                        <div class="group">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center">
                                    <div class="p-2 rounded-lg bg-indigo-50 text-indigo-600 mr-3 group-hover:bg-indigo-100 transition-colors">
                                        <i class="fas {{ $range['icon'] }} text-sm"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700 group-hover:text-indigo-600 transition-colors">{{ $range['name'] }}</span>
                                </div>
                                <span class="text-sm font-mono text-gray-500">
                                    {{ number_format($range['min'], 0, ',', '.') }} - {{ number_format($range['max'], 0, ',', '.') }} {{ $range['unit'] }}
                                </span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-blue-400 to-indigo-600 rounded-full transition-all duration-500 ease-out" style="width: 100%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 bg-gray-50 border-b">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Aktivitas Terkini</h3>
                <div class="relative">
                    <button type="button" class="inline-flex items-center justify-center p-2 rounded-full text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200" id="activity-options" aria-expanded="false" aria-haspopup="true">
                        <span class="sr-only">Buka opsi</span>
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <div class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none hidden" role="menu" aria-orientation="vertical" aria-labelledby="activity-options" tabindex="-1">
                        <div class="py-1" role="none">
                            <a href="#" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 transition-colors duration-150" role="menuitem" tabindex="-1">
                                <i class="fas fa-check-circle mr-2 text-green-500"></i>
                                Tandai semua sudah dibaca
                            </a>
                            <a href="#" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 transition-colors duration-150" role="menuitem" tabindex="-1">
                                <i class="fas fa-list mr-2 text-indigo-500"></i>
                                Lihat semua aktivitas
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }
    .card-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
    }
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }
    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }
</style>
@endpush
