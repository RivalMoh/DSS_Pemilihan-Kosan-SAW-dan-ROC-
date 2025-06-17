@extends('layouts.app')

@section('title', $kosan->nama . ' - ' . config('app.name'))

@push('styles')
<style>
    .kosan-gallery {
        margin-bottom: 30px;
    }
    
    .main-image {
        width: 100%;
        height: 400px;
        object-fit: cover;
        border-radius: 10px;
        margin-bottom: 15px;
    }
    
    .thumbnail-container {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
    }
    
    .thumbnail {
        width: 100%;
        height: 80px;
        object-fit: cover;
        border-radius: 5px;
        cursor: pointer;
        transition: opacity 0.3s;
    }
    
    .thumbnail:hover {
        opacity: 0.8;
    }
    
    .kosan-details {
        background: #fff;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }
    
    .kosan-title {
        font-size: 28px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 10px;
    }
    
    .kosan-location {
        color: #7f8c8d;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
    }
    
    .kosan-location i {
        margin-right: 5px;
    }
    
    .kosan-price {
        font-size: 24px;
        font-weight: 700;
        color: #e74c3c;
        margin-bottom: 20px;
    }
    
    .kosan-meta {
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }
    
    .meta-item {
        margin-right: 30px;
        margin-bottom: 10px;
    }
    
    .meta-item i {
        color: #3498db;
        margin-right: 5px;
    }
    
    .section-title {
        font-size: 20px;
        font-weight: 600;
        margin: 30px 0 15px;
        color: #2c3e50;
        position: relative;
        padding-bottom: 10px;
    }
    
    .section-title:after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 50px;
        height: 3px;
        background: #3498db;
    }
    
    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 30px;
    }
    
    .feature-item {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }
    
    .feature-item i {
        color: #2ecc71;
        margin-right: 10px;
    }
    
    .similar-kosan {
        margin-top: 50px;
    }
    
    .similar-kosan .card {
        border: none;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: transform 0.3s;
    }
    
    .similar-kosan .card:hover {
        transform: translateY(-5px);
    }
    
    .similar-kosan .card-img-top {
        height: 150px;
        object-fit: cover;
    }
    
    .similar-kosan .card-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .similar-kosan .card-text {
        color: #e74c3c;
        font-weight: 700;
    }
    
    .contact-btn {
        background: #3498db;
        color: white;
        border: none;
        padding: 10px 25px;
        border-radius: 5px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        transition: background 0.3s;
    }
    
    .contact-btn i {
        margin-right: 8px;
    }
    
    .contact-btn:hover {
        background: #2980b9;
        color: white;
        text-decoration: none;
    }
    
    .owner-info {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        margin-top: 30px;
    }
    
    .owner-name {
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .owner-phone {
        color: #7f8c8d;
        margin-bottom: 15px;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <!-- Kosan Gallery -->
            <div class="kosan-gallery">
                @if($kosan->foto && $kosan->foto->count() > 0)
                    <img id="mainImage" src="{{ asset('storage/' . ($kosan->foto_utama ?: $kosan->foto->first()->path)) }}" 
                         alt="{{ $kosan->nama }}" class="main-image">
                    
                    @if($kosan->foto->count() > 1)
                        <div class="thumbnail-container">
                            @foreach($kosan->foto as $foto)
                                <img src="{{ asset('storage/' . $foto->path) }}" 
                                     alt="{{ $kosan->nama }} - Foto {{ $loop->iteration }}" 
                                     class="thumbnail" 
                                     onclick="changeImage('{{ asset('storage/' . $foto->path) }}')">
                            @endforeach
                        </div>
                    @endif
                @else
                    <img id="mainImage" src="{{ asset('images/default-kosan.jpg') }}" 
                         alt="{{ $kosan->nama }}" class="main-image">
                @endif
            </div>
            
            <!-- Kosan Details -->
            <div class="kosan-details">
                <h1 class="kosan-title">{{ $kosan->nama }}</h1>
                <div class="kosan-location">
                    <i class="fas fa-map-marker-alt"></i>
                    {{ $kosan->alamat }}, {{ $kosan->kecamatan }}, {{ $kosan->kota }}, {{ $kosan->provinsi }}
                </div>
                
                <div class="kosan-price">Rp {{ number_format($kosan->harga, 0, ',', '.') }} <small>/bulan</small></div>
                
                <div class="kosan-meta">
                    <div class="meta-item">
                        <i class="fas fa-ruler-combined"></i>
                        {{ $kosan->luas_kamar }} m²
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-door-open"></i>
                        {{ $kosan->tipe_kamar }}
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-users"></i>
                        {{ $kosan->kapasitas }} Orang
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-walking"></i>
                        {{ $kosan->jarak_kampus }} km ke Kampus
                    </div>
                </div>
                
                <h3 class="section-title">Deskripsi</h3>
                <p>{{ $kosan->deskripsi }}</p>
                
                @if($kosan->fasilitasKamar->count() > 0)
                    <h3 class="section-title">Fasilitas Kamar</h3>
                    <div class="features-grid">
                        @foreach($kosan->fasilitasKamar as $fasilitas)
                            <div class="feature-item">
                                <i class="fas fa-check-circle"></i>
                                <span>{{ $fasilitas->nama }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
                
                @if($kosan->fasilitasKamarMandi->count() > 0)
                    <h3 class="section-title">Fasilitas Kamar Mandi</h3>
                    <div class="features-grid">
                        @foreach($kosan->fasilitasKamarMandi as $fasilitas)
                            <div class="feature-item">
                                <i class="fas fa-check-circle"></i>
                                <span>{{ $fasilitas->nama }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
                
                @if($kosan->fasilitasUmum->count() > 0)
                    <h3 class="section-title">Fasilitas Umum</h3>
                    <div class="features-grid">
                        @foreach($kosan->fasilitasUmum as $fasilitas)
                            <div class="feature-item">
                                <i class="fas fa-check-circle"></i>
                                <span>{{ $fasilitas->nama }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
                
                @if($kosan->akses_lokasi_pendukung->count() > 0)
                    <h3 class="section-title">Akses Lokasi Pendukung</h3>
                    <div class="features-grid">
                        @foreach($kosan->akses_lokasi_pendukung as $lokasi)
                            <div class="feature-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $lokasi->nama_lokasi }} 
                                    @if($lokasi->pivot->count > 1)
                                        ({{ $lokasi->pivot->count }}x)
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
                
                @if($kosan->peraturan)
                    <h3 class="section-title">Peraturan Kosan</h3>
                    <p>{{ $kosan->peraturan }}</p>
                @endif
                
                <div class="owner-info">
                    <h4>Informasi Pemilik</h4>
                    <div class="owner-name">{{ $kosan->user->name }}</div>
                    <div class="owner-phone">{{ $kosan->no_hp }}</div>
                    <a href="https://wa.me/{{ $kosan->no_hp }}?text=Saya%20tertarik%20dengan%20kosan%20{{ urlencode($kosan->nama) }}" 
                       class="contact-btn" target="_blank">
                        <i class="fab fa-whatsapp"></i> Hubungi via WhatsApp
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Contact Form -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Tanya Kosan Ini</h5>
                    <form id="contactForm">
                        @csrf
                        <input type="hidden" name="kosan_id" value="{{ $kosan->id }}">
                        
                        <div class="form-group">
                            <label for="name">Nama Lengkap</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Nomor Telepon</label>
                            <input type="tel" class="form-control" id="phone" name="phone" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Pesan</label>
                            <textarea class="form-control" id="message" name="message" rows="4" required>Saya tertarik dengan kosan {{ $kosan->nama }}. Mohon info lebih lanjut.</textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-block">Kirim Pesan</button>
                    </form>
                </div>
            </div>
            
            <!-- Location Map -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Lokasi</h5>
                    <div id="map" style="height: 250px; width: 100%;"></div>
                    <p class="mt-2 text-muted small">
                        <i class="fas fa-info-circle"></i> Lokasi perkiraan berdasarkan alamat yang terdaftar.
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Similar Kosan -->
    @if($similarKosan->count() > 0)
        <div class="similar-kosan">
            <h3 class="section-title">Kosan Serupa di {{ $kosan->kecamatan }}</h3>
            <div class="row">
                @foreach($similarKosan as $similar)
                    <div class="col-md-3 mb-4">
                        <div class="card h-100">
                            <img src="{{ $similar->foto_utama ? asset('storage/' . $similar->foto_utama) : asset('images/default-kosan.jpg') }}" 
                                 class="card-img-top" alt="{{ $similar->nama }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ Str::limit($similar->nama, 30) }}</h5>
                                <p class="card-text">Rp {{ number_format($similar->harga, 0, ',', '.') }}/bulan</p>
                                <a href="{{ route('kosan.show', $similar->id) }}" class="btn btn-sm btn-outline-primary">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Change main image when thumbnail is clicked
    function changeImage(src) {
        document.getElementById('mainImage').src = src;
    }
    
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
                            "lightness": 16
                        }
                    ]
                },
                {
                    "featureType": "transit",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#f2f2f2"
                        },
                        {
                            "lightness": 19
                        }
                    ]
                },
                {
                    "featureType": "water",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#e9e9e9"
                        },
                        {
                            "lightness": 17
                        }
                    ]
                }
            ]
        });
        
        // Add a marker at the location
        new google.maps.Marker({
            position: location,
            map: map,
            title: '{{ $kosan->nama }}'
        });
    }
    
    // Load the Google Maps API
    function loadGoogleMaps() {
        const script = document.createElement('script');
        script.src = `https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap`;
        script.async = true;
        script.defer = true;
        document.head.appendChild(script);
    }
    
    // Load the Google Maps API when the page loads
    window.onload = loadGoogleMaps;
    
    // Handle contact form submission
    document.getElementById('contactForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form data
        const formData = new FormData(this);
        
        // Send AJAX request
        fetch('{{ route("contact.submit") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(Object.fromEntries(formData))
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
