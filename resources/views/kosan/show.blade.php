@extends('layouts.main')

@section('title', $kosan->nama . ' - ' . config('app.name'))

@push('meta')
<meta name="description" content="{{ Str::limit($kosan->deskripsi, 160) }}">
<meta property="og:title" content="{{ $kosan->nama }} - {{ config('app.name') }}">
<meta property="og:description" content="{{ Str::limit($kosan->deskripsi, 160) }}">
@if($kosan->foto_utama)
<meta property="og:image" content="{{ asset('storage/' . $kosan->foto_utama) }}">
@endif
@endpush

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/lightgallery@2.7.2/css/lightgallery-bundle.min.css" rel="stylesheet">
<link href="https://unpkg.com/swiper@8/swiper-bundle.min.css" rel="stylesheet" />
<style>
    /* Modern Gallery Styles */
    .gallery-container {
        position: relative;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        margin-bottom: 2rem;
        background: white;
    }
    
    .swiper {
        width: 100%;
        height: 500px;
    }
    
    .swiper-slide {
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    
    .swiper-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .swiper-button-next,
    .swiper-button-prev {
        color: white;
        background: rgba(0, 0, 0, 0.5);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        transition: all 0.3s ease;
    }
    
    .swiper-button-next:after,
    .swiper-button-prev:after {
        font-size: 1.25rem;
    }
    
    .swiper-button-next:hover,
    .swiper-button-prev:hover {
        background: rgba(0, 0, 0, 0.8);
    }
    
    .swiper-pagination-bullet {
        background: white;
        opacity: 0.7;
    }
    
    .swiper-pagination-bullet-active {
        background: #4f46e5;
        opacity: 1;
    }
    
    .thumbnail-container {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 0.75rem;
        padding: 0.75rem;
        background: #f9fafb;
    }
    
    .thumbnail {
        width: 100%;
        height: 80px;
        object-fit: cover;
        border-radius: 0.5rem;
        cursor: pointer;
        transition: all 0.2s ease;
        border: 2px solid transparent;
    }
    
    .thumbnail:hover, .thumbnail.active {
        border-color: #4f46e5;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
    }
    
    /* Modern Detail Card Styles */
    .detail-card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        margin-bottom: 2rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        width: 100%;
        max-width: 100%;
    }
    
    /* Ensure images don't exceed container width */
    .swiper-slide img {
        max-width: 100%;
        height: auto;
    }
    
    .detail-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    .detail-header {
        padding: 1.5rem;
        border-bottom: 1px solid #f3f4f6;
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
    }
    
    .kosan-title {
        font-size: 2rem;
        font-weight: 800;
        color: #111827;
        margin-bottom: 0.5rem;
        line-height: 1.2;
    }
    
    .kosan-location {
        display: flex;
        align-items: center;
        color: #4b5563;
        margin-bottom: 1rem;
        font-size: 0.95rem;
    }
    
    .kosan-price {
        font-size: 1.75rem;
        font-weight: 800;
        color: #4f46e5;
        margin-bottom: 1rem;
        display: inline-block;
        background: rgba(79, 70, 229, 0.1);
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
    }
    
    .kosan-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        margin: 1.5rem 0;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        color: #4b5563;
    }
    
    .meta-item i {
        color: #4f46e5;
        margin-right: 0.5rem;
        font-size: 1.25rem;
    }
    
    /* Section Styles */
    .section {
        padding: 1.5rem;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .section:last-child {
        border-bottom: none;
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 1rem;
        position: relative;
        padding-bottom: 0.5rem;
    }
    
    .section-title:after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 3rem;
        height: 3px;
        background: #4f46e5;
        border-radius: 3px;
    }
    
    /* Features Grid */
    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .feature-item {
        display: flex;
        align-items: center;
        padding: 0.5rem 0;
        color: #4b5563;
    }
    
    .feature-item i {
        color: #10b981;
        margin-right: 0.5rem;
        font-size: 1.1rem;
    }
    
    /* Owner Info */
    .owner-info {
        background: #f9fafb;
        padding: 1.5rem;
        border-radius: 0.5rem;
        margin-top: 1.5rem;
    }
    
    .owner-name {
        font-weight: 600;
        color: #111827;
        margin-bottom: 0.25rem;
    }
    
    .owner-phone {
        color: #6b7280;
        margin-bottom: 1rem;
    }
    
    /* Contact Button */
    .contact-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #4f46e5;
        color: white;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        transition: all 0.2s ease;
        text-decoration: none;
        width: 100%;
        margin-bottom: 1rem;
    }
    
    .contact-btn i {
        margin-right: 0.5rem;
    }
    
    .contact-btn.whatsapp {
        background: #25D366;
    }
    
    .contact-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    
    .contact-btn:active {
        transform: translateY(0);
    }
    
    /* Map Container */
    .map-container {
        height: 250px;
        border-radius: 0.5rem;
        overflow: hidden;
        margin-top: 1rem;
    }
    
    /* Similar Kosan */
    .similar-kosan {
        margin-top: 3rem;
    }
    
    .similar-kosan .card {
        border: none;
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .similar-kosan .card {
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    
    .similar-kosan .card:hover {
        transform: translateY(-6px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
    }
    
    @media (min-width: 1024px) {
        .similar-kosan .card-image {
            height: 320px;
            overflow: hidden;
        }
        
        .similar-kosan .card-body {
            padding: 1.75rem;
        }
        
        .similar-kosan .card-title {
            font-size: 1.5rem;
            margin-bottom: 0.75rem;
        }
        
        .similar-kosan .card-address {
            font-size: 1rem;
            margin-bottom: 1.25rem;
        }
        
        .similar-kosan .card-price {
            font-size: 1.25rem;
        }
    }
    
    .similar-kosan .card-img-top {
        height: 160px;
        object-fit: cover;
    }
    
    .similar-kosan .card-body {
        padding: 1.25rem;
    }
    
    .similar-kosan .card-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 0.5rem;
    }
    
    .similar-kosan .card-price {
        color: #ef4444;
        font-weight: 700;
        margin-bottom: 0.75rem;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .main-image {
            height: 350px;
        }
        
        .thumbnail-container {
            grid-template-columns: repeat(4, 1fr);
        }
        
        .kosan-title {
            font-size: 1.5rem;
        }
        
        .kosan-price {
            font-size: 1.25rem;
        }
        
        .features-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 480px) {
        .main-image {
            height: 250px;
        }
        
        .thumbnail-container {
            grid-template-columns: repeat(3, 1fr);
        }
        
        .kosan-meta {
            gap: 1rem;
        }
        
        .meta-item {
            font-size: 0.875rem;
        }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Page Content -->
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Beranda
                        </a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-gray-400 mx-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                            <span class="text-sm font-medium text-gray-500">{{ $kosan->nama }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
            
            <!-- Main Content -->
            <div class="w-full space-y-8">
                <!-- Kosan Details -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-6">
                            <div class="flex-1">
                                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $kosan->nama }}</h1>
                                <div class="flex items-center text-gray-700 mb-4">
                                    <i class="fas fa-map-marker-alt text-indigo-500 mr-2"></i>
                                    <span>{{ $kosan->alamat }}</span>
                                </div>
                                
                                <!-- Kosan Meta -->
                                <div class="flex flex-wrap gap-3 mb-6">
                                    <div class="flex items-center bg-gray-50 px-4 py-3 rounded-lg flex-1 min-w-[200px]">
                                        <div class="p-2 bg-indigo-100 rounded-lg mr-3 text-indigo-600">
                                            <i class="fas fa-bed text-lg"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe Kost</p>
                                            <p class="font-semibold text-gray-900">{{ $kosan->tipe_kost }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center bg-gray-50 px-4 py-3 rounded-lg flex-1 min-w-[200px]">
                                        <div class="p-2 bg-indigo-100 rounded-lg mr-3 text-indigo-600">
                                            <i class="fas fa-ruler-combined text-lg"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Luas Kamar</p>
                                            <p class="font-semibold text-gray-900">{{ $kosan->luas_kamar }} m²</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center bg-gray-50 px-4 py-3 rounded-lg flex-1 min-w-[200px]">
                                        <div class="p-2 bg-indigo-100 rounded-lg mr-3 text-indigo-600">
                                            <i class="fas fa-door-open text-lg"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Tersedia</p>
                                            <p class="font-semibold text-gray-900">{{ $kosan->jumlah_kamar_tersedia }} Kamar</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="flex flex-wrap gap-3 mt-6">
                                    <button class="flex-1 sm:flex-none inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                        <i class="fas fa-phone-alt mr-2"></i> Hubungi Pemilik
                                    </button>
                                    <button class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-3 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                        <i class="far fa-heart mr-2"></i> Simpan
                                    </button>
                                    <button class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-3 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                        <i class="fas fa-share-alt mr-2"></i> Bagikan
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Price Box -->
                            <div class="bg-indigo-50 p-6 rounded-lg border border-indigo-100 min-w-[200px] text-center">
                                <p class="text-sm text-indigo-600 mb-1">Harga per Bulan</p>
                                <p class="text-2xl font-bold text-indigo-700">Rp {{ number_format($kosan->harga, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Description Section -->
                    <div class="border-t border-gray-100 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Deskripsi</h3>
                        <div class="prose max-w-none">
                            <p class="text-gray-700">{{ $kosan->deskripsi }}</p>
                        </div>
                    </div>
                
                @php
                    $fasilitasKamar = $kosan->fasilitas_kamar ?? collect();
                @endphp
                @if($fasilitasKamar->count() > 0)
                    <div class="section">
                        <h3 class="section-title">Fasilitas Kamar</h3>
                        <div class="features-grid">
                            @foreach($fasilitasKamar as $fasilitas)
                                <div class="feature-item">
                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                    <span class="text-gray-700">{{ $fasilitas->nama_fasilitas ?? $fasilitas->nama }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                @php
                    $fasilitasKamarMandi = $kosan->fasilitas_kamar_mandi ?? collect();
                @endphp
                @if($fasilitasKamarMandi->count() > 0)
                    <div class="section">
                        <h3 class="section-title">Fasilitas Kamar Mandi</h3>
                        <div class="features-grid">
                            @foreach($fasilitasKamarMandi as $fasilitas)
                                <div class="feature-item">
                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                    <span class="text-gray-700">{{ $fasilitas->nama_fasilitas ?? $fasilitas->nama }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                @php
                    $fasilitasUmum = $kosan->fasilitas_umum ?? collect();
                @endphp
                @if($fasilitasUmum->count() > 0)
                    <div class="section">
                        <h3 class="section-title">Fasilitas Umum</h3>
                        <div class="features-grid">
                            @foreach($fasilitasUmum as $fasilitas)
                                <div class="feature-item">
                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                    <span class="text-gray-700">{{ $fasilitas->nama_fasilitas ?? $fasilitas->nama }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                @php
                    $aksesLokasi = $kosan->akses_lokasi_pendukung ?? collect();
                @endphp
                @if($aksesLokasi->count() > 0)
                    <div class="section">
                        <h3 class="section-title">Akses Lokasi Pendukung</h3>
                        <div class="features-grid">
                            @foreach($aksesLokasi as $lokasi)
                                <div class="feature-item">
                                    <i class="fas fa-map-marker-alt text-blue-500 mr-2"></i>
                                    <span class="text-gray-700">
                                        {{ $lokasi->nama_lokasi ?? $lokasi->nama }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                @if($kosan->peraturan)
                    <div class="section">
                        <h3 class="section-title">Peraturan Kosan</h3>
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle text-yellow-500 text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">{{ $kosan->peraturan }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Owner Info -->
                <div class="owner-info bg-white rounded-xl shadow-md overflow-hidden mt-8">
                    <div class="p-6 text-center">
                        <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-indigo-100 flex items-center justify-center overflow-hidden">
                            <i class="fas fa-user-tie text-3xl text-indigo-600"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-1">Informasi Pemilik</h4>
                        <div class="owner-name text-xl font-medium text-gray-800">
                            {{ $kosan->user ? $kosan->user->name : 'Tidak Tersedia' }}
                        </div>
                        @php
                            $phoneNumber = $kosan->user->no_hp ?? ($kosan->no_hp ?? null);
                        @endphp
                        @if($phoneNumber)
                            <div class="owner-phone text-gray-600 mb-4">
                                <i class="fas fa-phone-alt mr-2"></i>{{ $phoneNumber }}
                            </div>
                            <div class="flex flex-col space-y-3">
                                <a href="https://wa.me/{{ $phoneNumber }}?text=Saya%20tertarik%20dengan%20kosan%20{{ urlencode($kosan->nama) }}" 
                                   class="contact-btn whatsapp flex items-center justify-center py-3 px-4 rounded-lg bg-green-500 hover:bg-green-600 text-white font-medium transition duration-200" 
                                   target="_blank">
                                    <i class="fab fa-whatsapp text-xl mr-2"></i> Chat via WhatsApp
                                </a>
                        @else
                            <div class="text-gray-500 text-sm mb-4">Nomor telepon tidak tersedia</div>
                            <div class="flex flex-col space-y-3">
                                <button disabled class="opacity-50 cursor-not-allowed contact-btn whatsapp flex items-center justify-center py-3 px-4 rounded-lg bg-green-500 text-white font-medium">
                                    <i class="fab fa-whatsapp text-xl mr-2"></i> Chat via WhatsApp
                                </button>
                        @endif
                            </a>
                            <button type="button" 
                                    class="contact-btn bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center"
                                    onclick="document.getElementById('contactForm').scrollIntoView({ behavior: 'smooth' });">
                                <i class="fas fa-envelope mr-2"></i> Kirim Pesan
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden mt-8" id="contactFormSection">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Tanya Kosan Ini</h3>
                        <form id="contactForm" class="space-y-4">
                            @csrf
                            <input type="hidden" name="kosan_id" value="{{ $kosan->id }}">
                            
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                                <input type="text" id="name" name="name" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200">
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" id="email" name="email" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200">
                            </div>
                            
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                                <input type="tel" id="phone" name="phone" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200">
                            </div>
                            
                            <div>
                                <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Pesan</label>
                                <textarea id="message" name="message" rows="4" required
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200">Saya tertarik dengan kosan {{ $kosan->nama }}. Mohon info lebih lanjut.</textarea>
                            </div>
                            
                            <button type="submit" 
                                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                                <i class="fas fa-paper-plane mr-2"></i> Kirim Pesan
                            </button>
                        </form>
                    </div>
                </div>
            
                <!-- Location Map -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden mt-8">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Lokasi</h3>
                        <div id="map" class="w-full h-64 rounded-lg overflow-hidden"></div>
                        <p class="mt-3 text-sm text-gray-500 flex items-center">
                            <i class="fas fa-info-circle mr-2"></i> Lokasi perkiraan berdasarkan alamat yang terdaftar.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Similar Kosan -->
        @if($similarKosan->count() > 0)
            <div class="mt-16">
                <h3 class="text-2xl font-bold text-gray-900 mb-8 text-center">Kosan Serupa di Sekitar</h3>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
                    @foreach($similarKosan as $similar)
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 flex flex-col h-full">
                            <div class="relative h-56 lg:h-auto lg:min-h-[320px] overflow-hidden">
                                <img src="{{ $similar->foto_utama ? asset('storage/' . $similar->foto_utama) : asset('images/default-kosan.jpg') }}" 
                                     class="w-full h-full object-cover transition-transform duration-500 hover:scale-105"
                                     alt="{{ $similar->nama }}">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent">
                                    <div class="absolute bottom-0 left-0 right-0 p-6">
                                        <h4 class="text-white font-bold text-xl lg:text-2xl mb-2 card-title">{{ $similar->nama }}</h4>
                                        <p class="text-white/90 text-sm lg:text-base mb-4 line-clamp-2 card-address">{{ $similar->alamat }}</p>
                                        <div class="flex justify-between items-center">
                                            <span class="text-yellow-300 font-bold text-lg lg:text-xl card-price">Rp {{ number_format($similar->harga_sewa, 0, ',', '.') }}/bln</span>
                                            <span class="bg-indigo-600 text-white text-sm font-semibold px-4 py-1.5 rounded-full">{{ $similar->tipe_kamar }}</span>
                                        </div>
                                    </div>
                                </div>
                                @if($similar->is_recommended ?? false)
                                    <div class="absolute top-4 left-4 bg-yellow-400 text-yellow-900 text-sm font-bold px-4 py-1.5 rounded-full flex items-center">
                                        <i class="fas fa-star mr-1.5"></i> Direkomendasikan
                                    </div>
                                @endif
                            </div>
                            <div class="p-5">
                                <div class="flex flex-wrap gap-3 mb-4">
                                    <div class="flex items-center bg-gray-50 rounded-full px-3 py-1.5">
                                        <i class="fas fa-ruler-combined text-indigo-500 mr-1.5"></i>
                                        <span class="text-sm font-medium text-gray-700">{{ $similar->luas_kamar ?? '0' }} m²</span>
                                    </div>
                                    <div class="flex items-center bg-gray-50 rounded-full px-3 py-1.5">
                                        <i class="fas fa-bed text-indigo-500 mr-1.5"></i>
                                        <span class="text-sm font-medium text-gray-700">{{ $similar->jumlah_kasur ?? '0' }} Kasur</span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center">
                                        <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                                        <span class="text-sm font-medium text-gray-700">{{ $similar->kecamatan->nama ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex items-center bg-yellow-50 px-3 py-1 rounded-full">
                                        <i class="fas fa-star text-yellow-400 mr-1.5"></i>
                                        <span class="text-sm font-bold text-yellow-700">{{ number_format($similar->rating ?? 0, 1) }}</span>
                                    </div>
                                </div>
                                <a href="{{ route('kosan.show', $similar->UniqueID) }}" 
                                   class="block w-full text-center bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg">
                                    Lihat Detail <i class="fas fa-arrow-right ml-2 text-xs"></i>
                                </a>
                            </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
<script>
    // Initialize Swiper
    document.addEventListener('DOMContentLoaded', function() {
        const swiper = new Swiper('.swiper', {
            // Optional parameters
            loop: true,
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
            speed: 500,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
        
        // Thumbnail click handler
        const thumbnails = document.querySelectorAll('.thumbnail');
        thumbnails.forEach(thumb => {
            thumb.addEventListener('click', function() {
                const index = parseInt(this.getAttribute('data-index'));
                swiper.slideTo(index + 1); // +1 because of loop: true
                
                // Update active thumbnail
                document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            });
        });
        
        // Update active thumbnail on slide change
        swiper.on('slideChange', function() {
            const activeIndex = swiper.realIndex; // Get real index for loop mode
            document.querySelectorAll('.thumbnail').forEach((thumb, index) => {
                if (index === activeIndex) {
                    thumb.classList.add('active');
                } else {
                    thumb.classList.remove('active');
                }
            });
        });
    });
    
    // Share functionality
    document.querySelectorAll('[data-share]').forEach(button => {
        button.addEventListener('click', async () => {
            try {
                if (navigator.share) {
                    await navigator.share({
                        title: '{{ $kosan->nama }}',
                        text: 'Lihat kosan ini: {{ $kosan->nama }}',
                        url: window.location.href,
                    });
                } else {
                    // Fallback for browsers that don't support Web Share API
                    const shareUrl = window.location.href;
                    await navigator.clipboard.writeText(shareUrl);
                    alert('Link berhasil disalin ke clipboard!');
                }
            } catch (err) {
                console.error('Error sharing:', err);
            }
        });
    });
    
    // Initialize map
    function initMap() {
        // For demo purposes, we'll use a default location
        // In production, you should use the actual coordinates from your database
        const location = { lat: -7.797068, lng: 110.370529 }; // Default to Yogyakarta
        
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 15,
            center: location,
            styles: [
                {
                    "featureType": "all",
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "saturation": 36
                        },
                        {
                            "color": "#333333"
                        },
                        {
                            "lightness": 40
                        }
                    ]
                },
                {
                    "featureType": "all",
                    "elementType": "labels.text.stroke",
                    "stylers": [
                        {
                            "visibility": "on"
                        },
                        {
                            "color": "#ffffff"
                        },
                        {
                            "lightness": 16
                        }
                    ]
                },
                {
                    "featureType": "all",
                    "elementType": "labels.icon",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "administrative",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#fefefe"
                        },
                        {
                            "lightness": 20
                        }
                    ]
                },
                {
                    "featureType": "administrative",
                    "elementType": "geometry.stroke",
                    "stylers": [
                        {
                            "color": "#fefefe"
                        },
                        {
                            "lightness": 17
                        },
                        {
                            "weight": 1.2
                        }
                    ]
                },
                {
                    "featureType": "landscape",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#f5f5f5"
                        },
                        {
                            "lightness": 20
                        }
                    ]
                },
                {
                    "featureType": "poi",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#f5f5f5"
                        },
                        {
                            "lightness": 21
                        }
                    ]
                },
                {
                    "featureType": "poi.park",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#dedede"
                        },
                        {
                            "lightness": 21
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#ffffff"
                        },
                        {
                            "lightness": 17
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "geometry.stroke",
                    "stylers": [
                        {
                            "color": "#ffffff"
                        },
                        {
                            "lightness": 29
                        },
                        {
                            "weight": 0.2
                        }
                    ]
                },
                {
                    "featureType": "road.arterial",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#ffffff"
                        },
                        {
                            "lightness": 18
                        }
                    ]
                },
                {
                    "featureType": "road.local",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#ffffff"
                        },
                        {
        
        geocoder.geocode({ 'address': address }, function(results, status) {
            if (status === 'OK') {
                // Use the first result's geometry
                const location = results[0].geometry.location;
                
                // Create map centered at the geocoded location
                map = new google.maps.Map(document.getElementById("map"), {
                    zoom: 15,
                    center: location,
                    styles: [
                        {
                            "featureType": "water",
                            "elementType": "geometry",
                            "stylers": [
                                { "color": "#e9e9e9" },
                                { "lightness": 17 }
                            ]
                        },
                        {
                            "featureType": "poi",
                            "stylers": [{ "visibility": "off" }]
                        },
                        {
                            "featureType": "transit",
                            "stylers": [{ "visibility": "off" }]
                        }
                    ]
                });
                
                // Add marker at the geocoded location
                marker = new google.maps.Marker({
                    position: location,
                    map: map,
                    title: "{{ $kosan->nama }}",
                    animation: google.maps.Animation.DROP
                });
                
                // Add info window
                const infoWindow = new google.maps.InfoWindow({
                    content: `
                        <div class="max-w-xs">
                            <h3 class="font-semibold text-gray-900">{{ $kosan->nama }}</h3>
                            <p class="text-sm text-gray-600">{{ $kosan->alamat }}</p>
                        </div>
                    `
                });
                
                marker.addListener('click', () => {
                    infoWindow.open(map, marker);
                });
                
                // Open info window by default
                infoWindow.open(map, marker);
                
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Pesan Anda telah terkirim. Kami akan segera menghubungi Anda.');
                this.reset();
            } else {
                alert('Terjadi kesalahan. Silakan coba lagi nanti.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan. Silakan coba lagi nanti.');
        });
    });
</script>
@endpush
