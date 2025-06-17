@extends('layouts.app')

@section('title', 'Tambah Kosan Baru - ' . config('app.name'))

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--multiple {
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        padding: 0.35rem 0.75rem;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #e5e7eb;
        border: 1px solid #9ca3af;
        border-radius: 0.25rem;
        padding: 0.25rem 0.5rem;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #6b7280;
        margin-right: 0.25rem;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: #1f2937;
    }
    .image-preview {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 10px;
    }
    .image-preview-item {
        position: relative;
        width: 100px;
        height: 100px;
        border: 1px solid #e5e7eb;
        border-radius: 0.375rem;
        overflow: hidden;
    }
    .image-preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .image-preview-item .remove-image {
        position: absolute;
        top: 2px;
        right: 2px;
        background: rgba(0, 0, 0, 0.6);
        color: white;
        border: none;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        padding: 0;
    }
</style>
@endpush

@section('content')
<div class="container py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Tambah Kosan Baru</h1>
            
            <form action="{{ route('kosan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Nama Kosan -->
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700">Nama Kosan <span class="text-red-500">*</span></label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('nama')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Alamat -->
                        <div>
                            <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat Lengkap <span class="text-red-500">*</span></label>
                            <textarea name="alamat" id="alamat" rows="3" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kecamatan & Kota -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="kecamatan" class="block text-sm font-medium text-gray-700">Kecamatan <span class="text-red-500">*</span></label>
                                <input type="text" name="kecamatan" id="kecamatan" value="{{ old('kecamatan') }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('kecamatan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="kota" class="block text-sm font-medium text-gray-700">Kota/Kabupaten <span class="text-red-500">*</span></label>
                                <input type="text" name="kota" id="kota" value="{{ old('kota', 'Yogyakarta') }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('kota')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi <span class="text-red-500">*</span></label>
                            <textarea name="deskripsi" id="deskripsi" rows="4" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Harga Per Bulan -->
                        <div>
                            <label for="harga_per_bulan" class="block text-sm font-medium text-gray-700">Harga Per Bulan <span class="text-red-500">*</span></label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <input type="number" name="harga_per_bulan" id="harga_per_bulan" value="{{ old('harga_per_bulan') }}" required
                                    class="pl-12 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="0">
                            </div>
                            @error('harga_per_bulan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tipe Kosan -->
                        <div>
                            <label for="tipe_kost" class="block text-sm font-medium text-gray-700">Tipe Kosan <span class="text-red-500">*</span></label>
                            <select name="tipe_kost" id="tipe_kost" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="" disabled selected>Pilih Tipe Kosan</option>
                                <option value="Putra" {{ old('tipe_kost') == 'Putra' ? 'selected' : '' }}>Putra</option>
                                <option value="Putri" {{ old('tipe_kost') == 'Putri' ? 'selected' : '' }}>Putri</option>
                                <option value="Campur" {{ old('tipe_kost') == 'Campur' ? 'selected' : '' }}>Campur</option>
                            </select>
                            @error('tipe_kost')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jumlah Kamar Tersedia -->
                        <div>
                            <label for="jumlah_kamar_tersedia" class="block text-sm font-medium text-gray-700">Jumlah Kamar Tersedia <span class="text-red-500">*</span></label>
                            <input type="number" name="jumlah_kamar_tersedia" id="jumlah_kamar_tersedia" value="{{ old('jumlah_kamar_tersedia', 1) }}" min="1" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('jumlah_kamar_tersedia')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Fasilitas Kamar -->
                        <div>
                            <label for="fasilitas_kamar" class="block text-sm font-medium text-gray-700">Fasilitas Kamar</label>
                            <select name="fasilitas_kamar[]" id="fasilitas_kamar" multiple
                                class="fasilitas-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @foreach(\App\Models\FasilitasKamar::all() as $fasilitas)
                                    <option value="{{ $fasilitas->id }}" {{ in_array($fasilitas->id, old('fasilitas_kamar', [])) ? 'selected' : '' }}>{{ $fasilitas->nama }}</option>
                                @endforeach
                            </select>
                            @error('fasilitas_kamar')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Fasilitas Kamar Mandi -->
                        <div>
                            <label for="fasilitas_kamar_mandi" class="block text-sm font-medium text-gray-700">Fasilitas Kamar Mandi</label>
                            <select name="fasilitas_kamar_mandi[]" id="fasilitas_kamar_mandi" multiple
                                class="fasilitas-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @foreach(\App\Models\FasilitasKamarMandi::all() as $fasilitas)
                                    <option value="{{ $fasilitas->id }}" {{ in_array($fasilitas->id, old('fasilitas_kamar_mandi', [])) ? 'selected' : '' }}>{{ $fasilitas->nama }}</option>
                                @endforeach
                            </select>
                            @error('fasilitas_kamar_mandi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Fasilitas Umum -->
                        <div>
                            <label for="fasilitas_umum" class="block text-sm font-medium text-gray-700">Fasilitas Umum</label>
                            <select name="fasilitas_umum[]" id="fasilitas_umum" multiple
                                class="fasilitas-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @foreach(\App\Models\FasilitasUmum::all() as $fasilitas)
                                    <option value="{{ $fasilitas->id }}" {{ in_array($fasilitas->id, old('fasilitas_umum', [])) ? 'selected' : '' }}>{{ $fasilitas->nama }}</option>
                                @endforeach
                            </select>
                            @error('fasilitas_umum')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Upload Foto -->
                        <div>
                            <label for="foto" class="block text-sm font-medium text-gray-700">Foto Kosan <span class="text-red-500">*</span></label>
                            <input type="file" name="foto[]" id="foto" multiple accept="image/*" 
                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                onchange="previewImages(this)">
                            @error('foto')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('foto.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <div id="imagePreview" class="image-preview mt-2"></div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-5 border-t border-gray-200">
                    <div class="flex justify-end">
                        <a href="{{ route('home') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Batal
                        </a>
                        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Simpan Kosan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2 for all select elements with class 'fasilitas-select'
        $('.fasilitas-select').select2({
            placeholder: 'Pilih fasilitas',
            allowClear: true,
            width: '100%',
            closeOnSelect: false
        });
    });

    // Image preview functionality
    function previewImages(input) {
        const preview = document.getElementById('imagePreview');
        preview.innerHTML = '';
        
        if (input.files) {
            Array.from(input.files).forEach((file, index) => {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'image-preview-item';
                    
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    
                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'remove-image';
                    removeBtn.innerHTML = '&times;';
                    removeBtn.onclick = function() {
                        // Remove the file from the file input
                        const dt = new DataTransfer();
                        const { files } = input;
                        
                        for (let i = 0; i < files.length; i++) {
                            if (index !== i) {
                                dt.items.add(files[i]);
                            }
                        }
                        
                        input.files = dt.files;
                        
                        // Remove the preview
                        div.remove();
                    };
                    
                    div.appendChild(img);
                    div.appendChild(removeBtn);
                    preview.appendChild(div);
                };
                
                reader.readAsDataURL(file);
            });
        }
    }
    
    // Allow removing images by clicking the remove button
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-image')) {
            e.target.parentElement.remove();
        }
    });
</script>
@endpush
