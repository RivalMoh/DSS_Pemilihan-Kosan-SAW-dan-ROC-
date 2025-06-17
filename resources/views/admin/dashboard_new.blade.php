@extends('admin.layouts.new')

@section('title', 'Dashboard Admin')

@push('styles')
<style>
    /* Base Styles */
    :root {
        --primary: #4f46e5;
        --primary-light: #818cf8;
        --success: #10b981;
        --info: #3b82f6;
        --warning: #f59e0b;
        --danger: #ef4444;
        --dark: #1f2937;
        --light: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-300: #d1d5db;
        --gray-700: #374151;
    }

    /* Card Styles */
    .card {
        @apply bg-white rounded-xl shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md;
        border-left: 4px solid;
    }

    .card-header {
        @apply px-6 py-4 border-b border-gray-100 bg-gray-50;
    }

    .card-title {
        @apply text-lg font-semibold text-gray-800 flex items-center;
    }

    .card-body {
        @apply p-6;
    }

    /* Stat Cards */
    .stat-card {
        @apply p-6 rounded-xl bg-white shadow-sm border-l-4 transition-all duration-300 hover:shadow-md;
    }

    .stat-icon {
        @apply w-12 h-12 rounded-xl flex items-center justify-center text-2xl text-white;
    }

    .stat-value {
        @apply text-2xl font-bold text-gray-900 mt-1;
    }

    .stat-label {
        @apply text-sm font-medium text-gray-500;
    }

    .stat-change {
        @apply inline-flex items-center text-xs font-medium px-2 py-1 rounded-full mt-2;
    }

    /* Progress Bar */
    .progress-container {
        @apply w-full bg-gray-200 rounded-full h-1.5 overflow-hidden mt-2;
    }

    .progress-bar {
        @apply h-full rounded-full;
    }

    /* Activity Item */
    .activity-item {
        @apply flex items-start py-3 border-b border-gray-100 last:border-0 last:pb-0 first:pt-0;
    }

    .activity-icon {
        @apply flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-white mr-3 mt-0.5;
    }

    /* Badges */
    .badge {
        @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium;
    }

    .badge-primary {
        @apply bg-indigo-100 text-indigo-800;
    }

    .badge-success {
        @apply bg-green-100 text-green-800;
    }

    .badge-warning {
        @apply bg-yellow-100 text-yellow-800;
    }

    .badge-danger {
        @apply bg-red-100 text-red-800;
    }

    /* Buttons */
    .btn {
        @apply inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors duration-200;
    }

    .btn-primary {
        @apply bg-indigo-600 text-white hover:bg-indigo-700 focus:ring-indigo-500;
    }

    .btn-sm {
        @apply px-3 py-1.5 text-xs;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-indigo-600 to-blue-600 rounded-2xl shadow-lg overflow-hidden mb-8">
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
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Kosan -->
        <div class="stat-card" style="border-color: var(--primary);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="stat-label">Total Kosan</p>
                    <h3 class="stat-value">{{ $stats['total_kosan'] ?? 0 }}</h3>
                    <span class="stat-change" style="background-color: rgba(16, 185, 129, 0.1); color: var(--success);">
                        <i class="fas fa-arrow-up mr-1"></i> 12%
                    </span>
                </div>
                <div class="stat-icon" style="background-color: var(--primary);">
                    <i class="fas fa-home"></i>
                </div>
            </div>
        </div>

        <!-- Kosan Tersedia -->
        <div class="stat-card" style="border-color: var(--success);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="stat-label">Kosan Tersedia</p>
                    <h3 class="stat-value">{{ $stats['available_kosan'] ?? 0 }}</h3>
                    <div class="mt-2">
                        <div class="flex items-center justify-between text-xs text-gray-500 mb-1">
                            <span>Ketersediaan</span>
                            <span>{{ $stats['available_kosan'] > 0 ? round(($stats['available_kosan'] / $stats['total_kosan']) * 100, 1) : 0 }}%</span>
                        </div>
                        <div class="progress-container">
                            <div class="progress-bar" style="width: {{ $stats['available_kosan'] > 0 ? ($stats['available_kosan'] / $stats['total_kosan']) * 100 : 0 }}%; background-color: var(--success);"></div>
                        </div>
                    </div>
                </div>
                <div class="stat-icon" style="background-color: var(--success);">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>

        <!-- Total Pengguna -->
        <div class="stat-card" style="border-color: var(--info);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="stat-label">Total Pengguna</p>
                    <h3 class="stat-value">{{ $stats['total_users'] ?? 0 }}</h3>
                    <span class="stat-change" style="background-color: rgba(59, 130, 246, 0.1); color: var(--info);">
                        <i class="fas fa-user-plus mr-1"></i> 5 baru hari ini
                    </span>
                </div>
                <div class="stat-icon" style="background-color: var(--info);">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>

        <!-- Total Kunjungan -->
        <div class="stat-card" style="border-color: var(--warning);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="stat-label">Total Kunjungan</p>
                    <h3 class="stat-value">{{ $stats['total_visits'] ?? 0 }}</h3>
                    <span class="stat-change" style="background-color: rgba(245, 158, 11, 0.1); color: var(--warning);">
                        <i class="fas fa-chart-line mr-1"></i> 24% peningkatan
                    </span>
                </div>
                <div class="stat-icon" style="background-color: var(--warning);">
                    <i class="fas fa-eye"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Weight Settings -->
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-sliders-h text-indigo-500 mr-2"></i>
                        Pengaturan Bobot Kriteria
                    </h2>
                </div>
                <div class="card-body">
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
                                    <span class="text-sm font-medium text-gray-700 group-hover:text-indigo-600 transition-colors">
                                        {{ $weight['name'] }}
                                    </span>
                                    <div class="flex items-center space-x-3">
                                        <span class="text-sm font-mono text-gray-500 w-12 text-right">
                                            {{ $weight['weight'] }}
                                        </span>
                                        <span class="badge {{ $weight['type'] === 'benefit' ? 'badge-success' : 'badge-danger' }}">
                                            <i class="fas {{ $weight['type'] === 'benefit' ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1 text-xs"></i>
                                            {{ ucfirst($weight['type']) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                    <div class="h-full rounded-full {{ $weight['type'] === 'benefit' ? 'bg-green-500' : 'bg-red-500' }} transition-all duration-500 ease-out" 
                                         style="width: {{ $weight['weight'] * 100 }}%">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6 text-right">
                        <a href="/admin/settings/weights" class="btn btn-primary">
                            <i class="fas fa-cog mr-2"></i> Kelola Bobot
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div>
            <div class="card h-full">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-history text-indigo-500 mr-2"></i>
                        Aktivitas Terkini
                    </h2>
                </div>
                <div class="card-body p-0">
                    <div class="divide-y divide-gray-100">
                        @php
                            $recentActivities = [
                                ['icon' => 'user-plus', 'color' => 'bg-green-500', 'text' => 'Pengguna baru "John Doe" terdaftar', 'time' => '5 menit yang lalu'],
                                ['icon' => 'home', 'color' => 'bg-blue-500', 'text' => 'Kosan "Kosan Sejahtera" ditambahkan', 'time' => '1 jam yang lalu'],
                                ['icon' => 'edit', 'color' => 'bg-yellow-500', 'text' => 'Data kosan "Kosan Bahagia" diperbarui', 'time' => '3 jam yang lalu'],
                                ['icon' => 'trash-alt', 'color' => 'bg-red-500', 'text' => 'Kosan "Kosan Makmur" dihapus', 'time' => 'Kemarin'],
                                ['icon' => 'cog', 'color' => 'bg-indigo-500', 'text' => 'Pengaturan sistem diperbarui', 'time' => '2 hari yang lalu']
                            ];
                        @endphp
                        @foreach($recentActivities as $activity)
                            <div class="activity-item">
                                <div class="activity-icon {{ $activity['color'] }}">
                                    <i class="fas fa-{{ $activity['icon'] }} text-xs"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $activity['text'] }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $activity['time'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="p-4 text-center border-t border-gray-100">
                        <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                            Lihat semua aktivitas
                            <i class="fas fa-arrow-right ml-1 text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attribute Ranges -->
    <div class="card mb-8">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-ruler-combined text-indigo-500 mr-2"></i>
                Rentang Atribut
            </h2>
        </div>
        <div class="card-body">
            <p class="text-sm text-gray-600 mb-6">Kelola rentang nilai untuk atribut numerik yang digunakan dalam rekomendasi.</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @php
                    $sampleRanges = [
                        ['name' => 'Harga Sewa', 'min' => '500000', 'max' => '5000000', 'unit' => '/bulan', 'icon' => 'fa-tag', 'color' => 'from-purple-500 to-indigo-600'],
                        ['name' => 'Luas Kamar', 'min' => '9', 'max' => '36', 'unit' => 'm²', 'icon' => 'fa-ruler-combined', 'color' => 'from-blue-500 to-cyan-500'],
                        ['name' => 'Jarak Kampus', 'min' => '0.1', 'max' => '5', 'unit' => 'km', 'icon' => 'fa-map-marker-alt', 'color' => 'from-green-500 to-emerald-500']
                    ];
                @endphp
                @foreach($sampleRanges as $range)
                    <div class="bg-gradient-to-br {{ $range['color'] }} rounded-xl p-0.5 shadow-sm">
                        <div class="bg-white p-5 rounded-xl">
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-2.5 rounded-lg bg-opacity-10 {{ str_replace('bg-gradient-to-br', 'bg', $range['color']) }} text-white">
                                    <i class="fas {{ $range['icon'] }} text-lg"></i>
                                </div>
                                <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-gray-100 text-gray-800">
                                    {{ $range['min'] }} - {{ $range['max'] }} {{ $range['unit'] }}
                                </span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $range['name'] }}</h3>
                            <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                <div class="h-full rounded-full bg-gradient-to-r {{ $range['color'] }} transition-all duration-500 ease-out"></div>
                            </div>
                            <div class="mt-4 flex justify-between text-xs text-gray-500">
                                <span>Min: {{ number_format($range['min'], 0, ',', '.') }}{{ $range['unit'] }}</span>
                                <span>Max: {{ number_format($range['max'], 0, ',', '.') }}{{ $range['unit'] }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-6 text-right">
                <a href="/admin/settings/attribute-ranges" class="btn btn-primary">
                    <i class="fas fa-sliders-h mr-2"></i> Kelola Rentang
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
