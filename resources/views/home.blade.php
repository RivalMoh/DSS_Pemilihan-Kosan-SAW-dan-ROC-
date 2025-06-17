@extends('layouts.main')

@section('title', 'Rekomendasi Kosan Terbaik')

@push('meta')
<meta name="description" content="Temukan rekomendasi kosan terbaik dengan sistem rekomendasi pintar kami. Dapatkan kosan dengan fasilitas terbaik dan harga terjangkau.">
<meta name="keywords" content="rekomendasi kosan, cari kosan, kosan terdekat, kosan murah, kosan nyaman, fasilitas kosan">
@endpush

@push('styles')
<style>
    /* Custom Pagination Styles */
    .pagination {
        @apply flex items-center space-x-1;
    }
    
    .page-item {
        @apply inline-flex items-center justify-center w-10 h-10 rounded-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50;
        margin: 0 2px;
    }
    
    .page-item.active .page-link {
        @apply bg-indigo-600 text-white border-indigo-600;
    }
    
    .page-item.disabled .page-link {
        @apply opacity-50 cursor-not-allowed;
    }
    
    .page-link {
        @apply w-full h-full flex items-center justify-center px-3 py-2;
    }
    
    .page-item:first-child .page-link,
    .page-item:last-child .page-link {
        @apply px-4;
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

@stack('select2-styles')
<style>
    /* Modal Styles */
    .modal-overlay {
        background-color: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
    }
    
    .modal-container {
        max-height: 90vh;
        overflow-y: auto;
    }
    
    /* Step Indicator */
    .step-indicator {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2rem;
        position: relative;
    }
    
    .step-indicator::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 2px;
        background-color: #e5e7eb;
        z-index: 0;
    }
    
    .step {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        z-index: 1;
    }
    
    .step-number {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        background-color: #e5e7eb;
        color: #6b7280;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        margin-bottom: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .step.active .step-number {
        background-color: #3b82f6;
        color: white;
    }
    
    .step.completed .step-number {
        background-color: #10b981;
        color: white;
    }
    
    .step-label {
        font-size: 0.75rem;
        font-weight: 500;
        color: #9ca3af;
        text-align: center;
        transition: all 0.3s ease;
    }
    
    .step.active .step-label,
    .step.completed .step-label {
        color: #111827;
        font-weight: 600;
    }
    
    /* Form Styles */
    .form-step {
        display: none;
    }
    
    .form-step.active {
        display: block;
        animation: fadeIn 0.3s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Image Upload Preview */
    .image-preview-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }
    
    .image-preview {
        position: relative;
        aspect-ratio: 1;
        border-radius: 0.5rem;
        overflow: hidden;
        border: 2px dashed #d1d5db;
    }
    
    .image-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .remove-image {
        position: absolute;
        top: 0.25rem;
        right: 0.25rem;
        background-color: rgba(239, 68, 68, 0.9);
        color: white;
        border-radius: 50%;
        width: 1.5rem;
        height: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        opacity: 0;
        transition: opacity 0.2s ease;
    }
    
    .image-preview:hover .remove-image {
        opacity: 1;
    }
    
    .upload-area {
        border: 2px dashed #d1d5db;
        border-radius: 0.5rem;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .upload-area:hover {
        border-color: #9ca3af;
        background-color: #f9fafb;
    }
    
    .upload-area.dragover {
        border-color: #3b82f6;
        background-color: #eff6ff;
    }
    
    /* Facility Selection */
    .facility-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
    }
    
    .facility-item {
        display: flex;
        align-items: center;
        padding: 0.5rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.375rem;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    
    .facility-item:hover {
        border-color: #9ca3af;
    }
    
    .facility-item.selected {
        border-color: #3b82f6;
        background-color: #eff6ff;
    }
    
    .facility-item input[type="checkbox"] {
        margin-right: 0.5rem;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 640px) {
        .facility-grid {
            grid-template-columns: 1fr;
        }
        
        .step-label {
            display: none;
        }
    }
    
    /* Filter Section Styles */
    .filter-section {
        transition: all 0.3s ease;
    }
    .filter-toggle {
        cursor: pointer;
    }
    .filter-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-out;
    }
    .filter-content.show {
        max-height: 1000px;
    }
    .price-range-slider {
        height: 5px;
    }
    .price-range-slider .noUi-connect {
        background: #4f46e5;
    }
    .noUi-handle {
        height: 18px !important;
        width: 18px !important;
        border-radius: 50%;
        top: -7px !important;
        right: -9px !important;
        background: #4f46e5;
        border: none;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .noUi-handle:before, .noUi-handle:after {
        display: none;
    }
</style>
@endpush

@section('content')
<div class="bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Hero Section -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl sm:tracking-tight lg:text-6xl">
                Temukan Kosan Impianmu
            </h1>
            <p class="mt-5 max-w-xl mx-auto text-xl text-gray-500">
                Temukan kosan terbaik dengan fasilitas lengkap dan harga terjangkau di kampus anda.
            </p>
        </div>

        <!-- Search and Filter -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <form action="{{ route('home') }}" method="GET" class="space-y-4">
                <!-- Main Search -->
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Cari berdasarkan lokasi, fasilitas, atau nama kosan...">
                </div>

                <!-- Filter Section with Alpine.js -->
                <div x-data="{
                    showFilters: false,
                    hasActiveFilters: {{ request()->hasAny(['min_price', 'max_price', 'room_size', 'location', 'facilities']) ? 'true' : 'false' }}
                }" class="space-y-4">
                    <!-- Filter Header -->
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">Filter Pencarian</h3>
                        <button @click="showFilters = !showFilters" type="button" class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500 focus:outline-none">
                            <span x-text="showFilters ? 'Sembunyikan Filter' : 'Filter Lanjutan'"></span>
                            <svg :class="{'transform rotate-180': showFilters}" class="ml-2 h-5 w-5 text-gray-400 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>

                    <!-- Filter Content -->
                    <div x-show="showFilters" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 mt-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <!-- Price Range -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Rentang Harga</label>
                                <div class="space-y-4 bg-gray-50 p-4 rounded-lg">
                                    <div class="price-range-slider"></div>
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <span class="text-xs text-gray-500 block">Min</span>
                                            <span class="text-sm font-medium" id="price-min">Rp 0</span>
                                        </div>
                                        <div class="text-gray-400">-</div>
                                        <div class="text-right">
                                            <span class="text-xs text-gray-500 block">Max</span>
                                            <span class="text-sm font-medium" id="price-max">Rp 5.000.000</span>
                                        </div>
                                    </div>
                                    <input type="hidden" name="min_price" id="min-price" value="{{ request('min_price', 0) }}">
                                    <input type="hidden" name="max_price" id="max-price" value="{{ request('max_price', 5000000) }}">
                                </div>
                            </div>

                            <!-- Room Size -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ukuran Kamar</label>
                                <div class="relative">
                                    <select name="room_size" class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                        <option value="">Semua Ukuran</option>
                                        @foreach($luasKamar as $size)
                                            <option value="{{ $size->id }}" {{ request('room_size') == $size->id ? 'selected' : '' }}>
                                                {{ $size->range_luas }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Location -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                                <div class="relative">
                                    <select name="location" class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                        <option value="">Semua Lokasi</option>
                                        <option value="surabaya" {{ request('location') == 'surabaya' ? 'selected' : '' }}>Surabaya</option>
                                        <option value="sidoarjo" {{ request('location') == 'sidoarjo' ? 'selected' : '' }}>Sidoarjo</option>
                                        <option value="gresik" {{ request('location') == 'gresik' ? 'selected' : '' }}>Gresik</option>
                                        <option value="mojokerto" {{ request('location') == 'mojokerto' ? 'selected' : '' }}>Mojokerto</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Facilities -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Fasilitas</label>
                                <div class="space-y-2 bg-gray-50 p-4 rounded-lg">
                                    <div class="flex items-center">
                                        <input id="wifi" name="facilities[]" type="checkbox" value="wifi" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" {{ in_array('wifi', (array)request('facilities', [])) ? 'checked' : '' }}>
                                        <label for="wifi" class="ml-2 text-sm text-gray-700">WiFi</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="ac" name="facilities[]" type="checkbox" value="ac" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" {{ in_array('ac', (array)request('facilities', [])) ? 'checked' : '' }}>
                                        <label for="ac" class="ml-2 text-sm text-gray-700">AC</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="kamar_mandi_dalam" name="facilities[]" type="checkbox" value="kamar_mandi_dalam" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" {{ in_array('kamar_mandi_dalam', (array)request('facilities', [])) ? 'checked' : '' }}>
                                        <label for="kamar_mandi_dalam" class="ml-2 text-sm text-gray-700">Kamar Mandi Dalam</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="pt-4 mt-4 border-t border-gray-100 flex justify-end space-x-3">
                            <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="-ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Reset Filter
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="-ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                Terapkan Filter
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Kosan Listings -->
        <div class="mb-12 py-6">
            <!-- Header with Title and Add Button -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Rekomendasi Kosan Terbaik</h2>
                <button type="button" 
                        onclick="window.dispatchEvent(new CustomEvent('open-add-kosan-modal'))" 
                        style="background-color: #1d4ed8; color: white;"
                        class="flex items-center gap-2 px-5 py-2 text-base font-bold rounded-lg border-2 border-blue-900 shadow-lg hover:shadow-xl hover:bg-blue-700 active:bg-blue-800 transition-all duration-200 ease-in-out transform hover:-translate-y-0.5">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="whitespace-nowrap">Tambah Kosan</span>
                </button>
            </div>
            
            <!-- Sorting Controls -->
            <div class="mb-6">
                <div class="flex items-center">
                    <span class="text-sm text-gray-500 font-medium mr-2 whitespace-nowrap">Urutkan:</span>
                    <select id="sort" class="w-full sm:w-64 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="latest">Terbaru</option>
                        <option value="price_asc">Harga Terendah</option>
                        <option value="price_desc">Harga Tertinggi</option>
                        <option value="distance">Jarak Terdekat</option>
                    </select>
                </div>
            </div>

            @if($kosan->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($kosan as $kost)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                            <div class="relative">
                                @if($kost->foto->count() > 0)
                                    <img src="{{ asset('storage/' . $kost->foto->first()->path) }}" alt="{{ $kost->nama }}" class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-500">No Image</span>
                                    </div>
                                @endif
                                @if(isset($recommendedKosan) && $recommendedKosan->where('kosan.UniqueID', $kost->UniqueID)->first())
                                    @php 
                                        $recommendation = $recommendedKosan->where('kosan.UniqueID', $kost->UniqueID)->first();
                                        $normalized = $recommendation['normalized_score'] ?? null;
                                        $score = $normalized !== null ? round($normalized * 100, 1) : 0;
                                        
                                        \Log::info('Recommendation Display', [
                                            'kosan_id' => $kost->UniqueID,
                                            'normalized' => $normalized,
                                            'final_score' => $score
                                        ]);
                                    @endphp
                                    <div class="absolute top-2 right-2 bg-blue-600 text-white text-xs font-bold px-2 py-1 rounded-full">
                                        Rekomendasi {{ $score }}%
                                    </div>
                                @endif
                                
                                @if($kost->is_recommended)
                                    <div class="absolute top-2 left-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Rekomendasi
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <div class="p-4">
                                <div class="flex justify-between items-start">
                                    <div class="flex items-center space-x-1 py-2">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $kost->nama }}</h3>
                                        <!--
                                        @if(isset($recommendedKosan) && $recommendedKosan->where('kosan.id', $kost->id)->first())
                                            @php 
                                                $scores = $recommendedKosan->where('kosan.id', $kost->id)->first()['scores'];
                                                $topCriteria = collect($scores)->sortDesc()->take(2);
                                            @endphp
                                            <div class="flex flex-wrap gap-1 mt-1">
                                                @foreach($topCriteria as $criteria => $score)
                                                    @if($score > 0)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                            {{ ucfirst(str_replace('_', ' ', $criteria)) }}: {{ number_format($score * 100) }}%
                                                        </span>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif -->
                                    </div>
                                    <div class="flex items-center space-x-1">
                                        @if(isset($recommendedKosan) && $recommendedKosan->where('kosan.UniqueID', $kost->UniqueID)->first())
                                            @php 
                                                $recommendation = $recommendedKosan->where('kosan.UniqueID', $kost->UniqueID)->first();
                                                $normalized = $recommendation['normalized_score'] ?? null;
                                                $score = $normalized !== null ? round($normalized * 100, 1) : 0;
                                                $scoreColor = $score >= 80 ? 'text-green-600' : 
                                                              ($score >= 60 ? 'text-blue-600' : 'text-yellow-600');
                                            @endphp
                                            <div class="text-sm {{ $scoreColor }}">
                                                <span class="font-medium">Skor Rekomendasi:</span> {{ $score }}%
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">
                                    <svg class="h-4 w-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    {{ $kost->alamat }}
                                </p>
                                <div class="mt-2 flex items-center text-sm text-gray-500">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    {{ $kost->tipe_kost }}
                                    <span class="mx-2">•</span>
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                    </svg>
                                    @if($kost->fasilitas_kamar->isNotEmpty())
                                        {{ $kost->fasilitas_kamar->implode('nama', ', ') }}
                                    @else
                                        Tidak ada fasilitas
                                    @endif
                                </div>
                                @if(isset($recommendedKosan) && $recommendedKosan->where('kosan.id', $kost->id)->first())
                                    @php 
                                        $scores = $recommendedKosan->where('kosan.id', $kost->id)->first()['scores'];
                                        $topCriteria = collect($scores)->sortDesc()->take(2);
                                    @endphp
                                    <!--
                                    <div class="mt-3 space-y-2">
                                        <div class="text-xs text-gray-500">Keunggulan:</div>
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($topCriteria as $criteria => $score)
                                                @if($score > 0)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700">
                                                        {{ ucfirst(str_replace('_', ' ', $criteria)) }}: {{ number_format($score * 100, 0) }}%
                                                    </span>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    -->
                                @endif
                                
                                <div class="mt-4 flex justify-between items-center relative" style="z-index: 50;">
                                    <div>
                                        <span class="text-lg font-bold text-indigo-600">Rp {{ number_format($kost->harga, 0, ',', '.') }}/bulan</span>
                                        @if($kost->jumlah_kamar_tersedia > 0)
                                            <span class="block text-sm text-green-600">{{ $kost->jumlah_kamar_tersedia }} kamar tersedia</span>
                                        @else
                                            <span class="block text-sm text-red-600">Penuh</span>
                                        @endif
                                    </div>
                                    <a href="{{ route('kosan.show', $kost->UniqueID) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-black bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" style="position: relative; z-index: 9999; min-width: 100px; text-align: center;">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-12 flex justify-center py-4">
                    <nav class="inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        {{ $kosan->appends(request()->query())->onEachSide(1)->links('pagination::tailwind') }}
                    </nav>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada kosan yang ditemukan</h3>
                    <p class="mt-1 text-sm text-gray-500">Coba ubah filter pencarian Anda atau hapus beberapa filter untuk melihat lebih banyak hasil.</p>
                    <div class="mt-6">
                        <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Reset Filter
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Include Add Kosan Modal -->
@include('components.add-kosan-modal', ['facilities' => $facilities])

@push('scripts')
<!-- jQuery first, then Select2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@stack('select2-scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.5.0/nouislider.min.js"></script>

<script>
    // Toggle filter content
    document.querySelector('.filter-toggle').addEventListener('click', function() {
        const filterContent = document.getElementById('filter-content');
        const filterArrow = document.getElementById('filter-arrow');
        
        filterContent.classList.toggle('show');
        filterArrow.classList.toggle('rotate-180');
    });

    // Initialize price range slider
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Pilih opsi',
            allowClear: true
        });

        // Price slider initialization
        const priceSlider = document.querySelector('.price-range-slider');
        const minPrice = document.getElementById('min-price');
        const maxPrice = document.getElementById('max-price');
        const minPriceLabel = document.getElementById('price-min');
        const maxPriceLabel = document.getElementById('price-max');

        if (priceSlider) {
            noUiSlider.create(priceSlider, {
                start: [parseInt(minPrice.value), Math.min(parseInt(maxPrice.value), 5000000)],
                connect: true,
                range: {
                    'min': 0,
                    'max': 5000000
                },
                step: 100000,
                format: {
                    to: function(value) {
                        return parseInt(value);
                    },
                    from: function(value) {
                        return parseInt(value);
                    }
                }
            });

            priceSlider.noUiSlider.on('update', function(values, handle) {
                const value = parseInt(values[handle]);
                if (handle) {
                    maxPrice.value = value;
                    maxPriceLabel.textContent = 'Rp ' + value.toLocaleString('id-ID');
                } else {
                    minPrice.value = value;
                    minPriceLabel.textContent = 'Rp ' + value.toLocaleString('id-ID');
                }
            });
        }


        // Handle sort change
        const sortSelect = document.getElementById('sort');
        if (sortSelect) {
            sortSelect.addEventListener('change', function() {
                const url = new URL(window.location.href);
                url.searchParams.set('sort', this.value);
                window.location.href = url.toString();
            });
        }

        
        // Handle form submission for Add Kosan
        const form = document.getElementById('add-kosan-form');
        if (form) {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const submitButton = form.querySelector('button[type="submit"]');
                const submitButtonText = submitButton.innerHTML;
                submitButton.disabled = true;
                submitButton.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Menyimpan...';
                
                try {
                    const formData = new FormData(form);
                    
                    // Add CSRF token
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    
                    const response = await fetch('{{ route("kosan.store") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    });
                    
                    const data = await response.json();
                    
                    if (response.ok) {
                        // Show success message
                        const successEvent = new CustomEvent('show-toast', {
                            detail: {
                                type: 'success',
                                message: 'Kosan berhasil ditambahkan!',
                                duration: 5000
                            }
                        });
                        window.dispatchEvent(successEvent);
                        
                        // Redirect to the new kosan page
                        window.location.href = data.redirect;
                    } else {
                        // Show error message
                        const errorEvent = new CustomEvent('show-toast', {
                            detail: {
                                type: 'error',
                                message: data.message || 'Terjadi kesalahan. Silakan coba lagi.',
                                duration: 5000
                            }
                        });
                        window.dispatchEvent(errorEvent);
                        
                        // Re-enable the submit button
                        submitButton.disabled = false;
                        submitButton.innerHTML = submitButtonText;
                        
                        // Handle validation errors
                        if (data.errors) {
                            // You can add more specific error handling here
                            console.error('Validation errors:', data.errors);
                        }
                    }
                } catch (error) {
                    console.error('Error:', error);
                    
                    // Show error message
                    const errorEvent = new CustomEvent('show-toast', {
                        detail: {
                            type: 'error',
                            message: 'Terjadi kesalahan. Silakan coba lagi.',
                            duration: 5000
                        }
                    });
                    window.dispatchEvent(errorEvent);
                    
                    // Re-enable the submit button
                    submitButton.disabled = false;
                    submitButton.innerHTML = submitButtonText;
                }
            });
        }
        
        // Toast notification system
        if (!document.getElementById('toast-container')) {
            const toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.className = 'fixed top-4 right-4 z-50 space-y-4 w-80';
            document.body.appendChild(toastContainer);
        }
        
        // Listen for toast events
        window.addEventListener('show-toast', function(e) {
            const { type, message, duration = 5000 } = e.detail;
            
            const toast = document.createElement('div');
            toast.className = `p-4 rounded-lg shadow-lg ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white flex items-start justify-between`;
            toast.role = 'alert';
            
            const messageEl = document.createElement('div');
            messageEl.className = 'flex-1';
            messageEl.textContent = message;
            
            const closeButton = document.createElement('button');
            closeButton.className = 'ml-4 text-white hover:text-gray-200';
            closeButton.innerHTML = '&times;';
            closeButton.onclick = () => {
                toast.classList.add('opacity-0', 'translate-x-full', 'transition-all', 'duration-300');
                setTimeout(() => toast.remove(), 300);
            };
            
            toast.appendChild(messageEl);
            toast.appendChild(closeButton);
            
            // Add to container
            document.getElementById('toast-container').appendChild(toast);
            
            // Animate in
            setTimeout(() => {
                toast.classList.add('opacity-100', 'translate-x-0');
            }, 10);
            
            // Auto-remove after duration
            if (duration > 0) {
                setTimeout(() => {
                    toast.classList.add('opacity-0', 'translate-x-full', 'transition-all', 'duration-300');
                    setTimeout(() => toast.remove(), 300);
                }, duration);
            }
        });
    });
</script>
@endpush

@endsection
