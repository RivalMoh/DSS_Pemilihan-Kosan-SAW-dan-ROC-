@extends('admin.layouts.new')

@section('title', 'Dashboard Admin')

@section('content')
<div class="space-y-6">
    <!-- Welcome Banner -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="px-6 py-8 sm:p-10">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Selamat Datang, {{ Auth::user()->name }}!</h2>
                    <p class="mt-2 text-gray-600">Ini adalah panel admin {{ config('app.name') }}. Kelola konten dan pengaturan dengan mudah dari sini.</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <div class="flex items-center">
                        <div class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center">
                            <i class="fas fa-home text-indigo-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Kosan</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_kosan'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total Kosan -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                        <i class="fas fa-home text-white text-xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Kosan</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ $stats['total_kosan'] ?? 0 }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kosan Tersedia -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                        <i class="fas fa-check-circle text-white text-xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Kosan Tersedia</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ $stats['available_kosan'] ?? 0 }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Pengguna -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Pengguna</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ $stats['total_users'] ?? 0 }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Kunjungan -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                        <i class="fas fa-eye text-white text-xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Kunjungan</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ $stats['total_visits'] ?? 0 }}</div>
                            </dd>
                        </dl>
                    </div>
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
                        Kelola
                    </a>
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Pengaturan Bobot</h6>
                </div>
                <div class="card-body">
                    <p>Kelola bobot kriteria untuk sistem rekomendasi. Pastikan total bobot sama dengan 1.</p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="bg-light">
                                <tr>
                                    <th>Kriteria</th>
                                    <th>Bobot</th>
                                    <th>Tipe</th>
                                </tr>
                            </thead>
                            <tbody>
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
                                    <tr>
                                        <td>{{ $weight['name'] }}</td>
                                        <td>{{ $weight['weight'] }}</td>
                                        <td>
                                            <span class="badge {{ $weight['type'] === 'benefit' ? 'badge-success' : 'badge-danger' }}">
                                                {{ ucfirst($weight['type']) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-right mt-3">
                        <a href="/admin/settings/weights" class="btn btn-primary">
                            <i class="fas fa-cog mr-1"></i> Kelola Bobot
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attribute Ranges Card -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Rentang Atribut</h6>
                </div>
                <div class="card-body">
                    <p>Kelola rentang nilai untuk atribut numerik yang digunakan dalam perhitungan.</p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="bg-light">
                                <tr>
                                    <th>Atribut</th>
                                    <th>Min</th>
                                    <th>Max</th>
                                    <th>Grup</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $sampleRanges = [
                                        ['name' => 'Harga Sewa', 'min' => '500,000', 'max' => '5,000,000', 'groups' => 5],
                                        ['name' => 'Luas Kamar', 'min' => '9', 'max' => '36', 'groups' => 5],
                                        ['name' => 'Jarak Kampus', 'min' => '0.1', 'max' => '10.0', 'groups' => 5]
                                    ];
                                @endphp
                                @foreach($sampleRanges as $range)
                                    <tr>
                                        <td>{{ $range['name'] }}</td>
                                        <td>{{ $range['min'] }}</td>
                                        <td>{{ $range['max'] }}</td>
                                        <td>{{ $range['groups'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-right mt-3">
                        <a href="/admin/settings/attribute-ranges" class="btn btn-primary">
                            <i class="fas fa-sliders-h mr-1"></i> Kelola Rentang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Aktivitas Terkini</h6>
            <a href="#" class="btn btn-sm btn-primary">Lihat Semua</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama Kosan</th>
                            <th>Alamat</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentKosan as $kosan)
                            <tr>
                                <td>{{ $kosan->nama }}</td>
                                <td>{{ Str::limit($kosan->alamat, 50) }}</td>
                                <td>Rp {{ number_format($kosan->harga, 0, ',', '.') }}/bulan</td>
                                <td>
                                    @if($kosan->is_available)
                                        <span class="badge badge-success">Tersedia</span>
                                    @else
                                        <span class="badge badge-danger">Tidak Tersedia</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-info">Lihat</a>
                                    <a href="#" class="btn btn-sm btn-warning">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data kosan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
