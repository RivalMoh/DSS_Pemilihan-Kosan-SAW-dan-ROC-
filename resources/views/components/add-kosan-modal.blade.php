@props([
    'facilities',
    'showButton' => true
])

@if($showButton)
<button @click="open()" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md shadow-sm transition-colors duration-150">
    Tambah Kosan Baru
</button>
@endif

<div x-data="addKosanModal()" x-init="init()" x-ref="dialog" x-cloak class="fixed inset-0 z-50 overflow-y-auto" x-show="isOpen" x-transition:enter="ease-out duration-300" x-transition:leave="ease-in duration-200" @keydown.escape.window="close()">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
         @click="close()"
         x-show="isOpen"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         aria-hidden="true">
    </div>

    <!-- Modal container -->
    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:block sm:p-0">
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <!-- Modal panel -->
        <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full"
             x-show="isOpen"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             style="max-height: 90vh; min-width: 300px; width: 90%; max-width: 800px;">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-start sm:items-center">
                <div class="w-full">
                    <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                        Tambah Kosan Baru
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">Lengkapi formulir di bawah untuk menambahkan kosan baru</p>
                </div>
                <button type="button" 
                        @click="close()" 
                        class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        aria-label="Tutup">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Modal Content -->
            <div class="px-6 py-6 overflow-y-auto" style="max-height: calc(90vh - 150px);">
                <div class="space-y-6">
                    <!-- Progress Bar -->
                    <div class="mt-2">
                        <div class="flex justify-between mb-2">
                            @foreach([1 => 'Informasi Umum', 2 => 'Fasilitas', 3 => 'Foto & Detail Lainnya'] as $step => $label)
                                <div class="flex flex-col items-center">
                                    <div :class="{
                                        'w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium border-2': true,
                                        'bg-indigo-100 border-indigo-600 text-indigo-700': currentStep === {{ $step }},
                                        'bg-white-100 border-indigo-600 text-indigo-700': currentStep > {{ $step }},
                                        'border-gray-300 bg-gray-50 text-gray-400': currentStep < {{ $step }}
                                    }">
                                        {{ $step }}
                                    </div>
                                    <span :class="{
                                        'mt-2 text-xs font-medium': true,
                                        'text-indigo-600': currentStep >= {{ $step }},
                                        'text-gray-400': currentStep < {{ $step }}
                                    }">{{ $label }}</span>
                                </div>
                                @if($step < 3)
                                    <div class="flex-1 flex items-center">
                                        <div class="h-0.5 w-full bg-gray-200"></div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Form Steps -->
                    <form id="addKosanForm" action="{{ route('kosan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <!-- Step 1: General Information -->
                        <div x-show="currentStep === 1">
                            <div class="space-y-6">
                                <!-- Nama Kosan -->
                                <div class="w-full">
                                    <label for="nama_kosan" class="block text-sm font-medium text-gray-700 mb-1">Nama Kosan <span class="text-red-500">*</span></label>
                                    <input type="text" name="nama_kosan" id="nama_kosan" x-model="formData.nama_kosan" required
                                        class="block w-full rounded-md border-gray-300 shadow-sm py-2 px-3 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border">
                                    <p x-show="errors.nama_kosan" x-text="errors.nama_kosan" class="mt-1 text-xs text-red-600"></p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Left Column -->
                                    <div class="space-y-5">

                                        <!-- Room Size -->
                                        <div class="w-full">
                                            <label for="luas_kamar" class="block text-sm font-medium text-gray-700 mb-1">Luas Kamar (m²) <span class="text-red-500">*</span></label>
                                            <input type="number" name="luas_kamar" id="luas_kamar" x-model="formData.luas_kamar" step="0.1" min="9" required
                                                class="block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <p x-show="errors.luas_kamar" x-text="errors.luas_kamar" class="mt-1 text-xs text-red-600"></p>
                                        </div>

                                        <!-- Tipe Kost -->
                                        <div class="w-full">
                                            <label for="tipe_kost" class="block text-sm font-medium text-gray-700">Tipe Kost <span class="text-red-500">*</span></label>
                                            <select id="tipe_kost" name="tipe_kost" x-model="formData.tipe_kost" required
                                                class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                                <option value="" disabled selected>Pilih Tipe Kost</option>
                                                @foreach($facilities['tipe_kost'] as $tipe)
                                                    <option value="{{ $tipe }}">{{ $tipe }}</option>
                                                @endforeach
                                            </select>
                                            <p x-show="errors.tipe_kost" x-text="errors.tipe_kost" class="mt-1 text-xs text-red-600"></p>
                                        </div>

                                        <!-- Harga Per Bulan -->
                                        <div class="w-full">
                                            <label for="harga" class="block text-sm font-medium text-gray-700 mb-1">Harga Per Bulan (Rp) <span class="text-red-500">*</span></label>
                                            <div class="relative">
                                                <input type="number" name="harga" id="harga" x-model="formData.harga" min="100000" required
                                                    class="block w-full py-2 pl-10 pr-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                    placeholder="0">
                                            </div>
                                            <p x-show="errors.harga" x-text="errors.harga" class="mt-1 text-xs text-red-600"></p>
                                        </div>

                                        <!-- Jumlah Kamar Tersedia -->
                                        <div class="w-full">
                                            <label for="jumlah_kamar_tersedia" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Kamar Tersedia <span class="text-red-500">*</span></label>
                                            <div class="relative">
                                                <input type="number" name="jumlah_kamar_tersedia" id="jumlah_kamar_tersedia" x-model="formData.jumlah_kamar_tersedia" min="1" required
                                                    class="block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            </div>
                                            <p x-show="errors.jumlah_kamar_tersedia" x-text="errors.jumlah_kamar_tersedia" class="mt-1 text-xs text-red-600"></p>
                                        </div>
                                    </div>

                                    <!-- Right Column -->
                                    <div class="space-y-5">
                                        <!-- Alamat -->
                                        <div class="w-full">
                                            <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
                                            <input type="text" id="alamat" name="alamat" x-model="formData.alamat" @keyup="clearError('alamat')" :class="{'border-red-500': errors.alamat}" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Alamat Lengkap">
                                            <p x-show="errors.alamat" x-text="errors.alamat" class="mt-1 text-sm text-red-600"></p>
                                        </div>

                                        <!-- Jarak ke Kampus (Meter) -->
                                        <div class="w-full">
                                            <label for="jarak_kampus" class="block text-sm font-medium text-gray-700">Jarak ke Kampus (km) <span class="text-red-500">*</span></label>
                                            <div class="mt-1 relative rounded-md shadow-sm">
                                                <input type="number" step="0.1" name="jarak_kampus" id="jarak_kampus" x-model="formData.jarak_kampus" min="0.1" max="20" required
                                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            </div>
                                            <p x-show="errors.jarak_kampus" x-text="errors.jarak_kampus" class="mt-1 text-sm text-red-600"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Facilities -->
                        <div x-show="currentStep === 2" class="space-y-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Left Column -->
                                <div class="space-y-6">
                                    <!-- Fasilitas Kamar -->
                                    <div class="bg-white p-4 rounded-lg shadow">
                                        <label class="block text-sm font-semibold text-gray-800 mb-3">Fasilitas Kamar</label>
                                        <div class="space-y-2 max-h-60 overflow-y-auto">
                                            @foreach($facilities['kamar'] as $facility)
                                                <div class="flex items-center">
                                                    <input type="checkbox" 
                                                           id="kamar_{{ $facility->id }}" 
                                                           name="fasilitas_kamar[]" 
                                                           value="{{ $facility->id }}"
                                                           x-model="formData.fasilitas_kamar"
                                                           @click="
                                                               const value = '{{ $facility->id }}';
                                                               const index = formData.fasilitas_kamar.indexOf(value);
                                                               if ($event.target.checked) {
                                                                   if (index === -1) formData.fasilitas_kamar.push(value);
                                                               } else {
                                                                   formData.fasilitas_kamar = formData.fasilitas_kamar.filter(item => item !== value);
                                                               }
                                                           "
                                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                    <label for="kamar_{{ $facility->id }}" class="ml-2 text-sm text-gray-700 hover:text-indigo-600 transition-colors duration-200 cursor-pointer px-2">{{ $facility->nama_fasilitas }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Fasilitas Kamar Mandi -->
                                    <div class="bg-white p-4 rounded-lg shadow">
                                        <label class="block text-sm font-semibold text-gray-800 mb-3">Fasilitas Kamar Mandi</label>
                                        <div class="space-y-2 max-h-60 overflow-y-auto">
                                            @foreach($facilities['kamar_mandi'] as $facility)
                                                <div class="flex items-center">
                                                    <input type="checkbox" 
                                                           id="kamar_mandi_{{ $facility->id }}" 
                                                           name="fasilitas_kamar_mandi[]" 
                                                           value="{{ $facility->id }}"
                                                           x-model="formData.fasilitas_kamar_mandi"
                                                           @click="
                                                               const value = '{{ $facility->id }}';
                                                               const index = formData.fasilitas_kamar_mandi.indexOf(value);
                                                               if ($event.target.checked) {
                                                                   if (index === -1) formData.fasilitas_kamar_mandi.push(value);
                                                               } else {
                                                                   formData.fasilitas_kamar_mandi = formData.fasilitas_kamar_mandi.filter(item => item !== value);
                                                               }
                                                           "
                                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                    <label for="kamar_mandi_{{ $facility->id }}" class="ml-2 text-sm text-gray-700 hover:text-indigo-600 transition-colors duration-200 cursor-pointer px-2">{{ $facility->nama_fasilitas }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column -->
                                <div class="space-y-6">
                                    <!-- Fasilitas Umum -->
                                    <div class="bg-white p-4 rounded-lg shadow">
                                        <label class="block text-sm font-semibold text-gray-800 mb-3">Fasilitas Umum</label>
                                        <div class="space-y-2 max-h-60 overflow-y-auto">
                                            @foreach($facilities['umum'] as $facility)
                                                <div class="flex items-center">
                                                    <input type="checkbox" 
                                                           id="umum_{{ $facility->id }}" 
                                                           name="fasilitas_umum[]" 
                                                           value="{{ $facility->id }}"
                                                           x-model="formData.fasilitas_umum"
                                                           @click="
                                                               const value = '{{ $facility->id }}';
                                                               const index = formData.fasilitas_umum.indexOf(value);
                                                               if ($event.target.checked) {
                                                                   if (index === -1) formData.fasilitas_umum.push(value);
                                                               } else {
                                                                   formData.fasilitas_umum = formData.fasilitas_umum.filter(item => item !== value);
                                                               }
                                                           "
                                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                    <label for="umum_{{ $facility->id }}" class="ml-2 text-sm text-gray-700 hover:text-indigo-600 transition-colors duration-200 cursor-pointer px-2">{{ $facility->nama_fasilitas }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Akses Lokasi Pendukung -->
                                    <div class="bg-white p-4 rounded-lg shadow">
                                        <label class="block text-sm font-semibold text-gray-800 mb-3">Akses Lokasi Pendukung</label>
                                        <p class="text-xs text-gray-500 mb-3">Pilih lokasi terdekat yang ada di sekitar kosan</p>
                                        <div class="space-y-2 max-h-60 overflow-y-auto">
                                            @if(isset($facilities['akses_lokasi']) && count($facilities['akses_lokasi']) > 0)
                                                @foreach($facilities['akses_lokasi'] as $lokasi)
                                                    <div class="flex items-center">
                                                        <input type="checkbox" 
                                                               id="akses_lokasi_{{ $lokasi->id }}" 
                                                               name="akses_lokasi_pendukung[]" 
                                                               value="{{ $lokasi->id }}"
                                                               x-model="formData.akses_lokasi_pendukung"
                                                               @click="
                                                                   const value = '{{ $lokasi->id }}';
                                                                   const index = formData.akses_lokasi_pendukung ? formData.akses_lokasi_pendukung.indexOf(value) : -1;
                                                                   if ($event.target.checked) {
                                                                       if (!formData.akses_lokasi_pendukung) formData.akses_lokasi_pendukung = [];
                                                                       if (index === -1) formData.akses_lokasi_pendukung.push(value);
                                                                   } else {
                                                                       formData.akses_lokasi_pendukung = formData.akses_lokasi_pendukung.filter(item => item !== value);
                                                                   }
                                                               "
                                                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                        <label for="akses_lokasi_{{ $lokasi->id }}" class="ml-2 text-sm text-gray-700 hover:text-indigo-600 transition-colors duration-200 cursor-pointer px-2">{{ $lokasi->nama_lokasi }}</label>
                                                    </div>
                                                @endforeach
                                            @else
                                                <p class="text-sm text-gray-500">Tidak ada data akses lokasi tersedia</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Photos & Other Details -->
                        <div x-show="currentStep === 3">
                            <div class="space-y-6">
                                <!-- Kebersihan Dropdown -->
                                <div class="w-full">
                                    <label for="kebersihan_id" class="block text-sm font-medium text-gray-700">Tingkat Kebersihan <span class="text-red-500">*</span></label>
                                    <select id="kebersihan_id" name="kebersihan_id" x-model="formData.kebersihan_id" required
                                        class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                        <option value="" disabled selected>Pilih Tingkat Kebersihan</option>
                                        @foreach($facilities['kebersihan'] as $kebersihan)
                                            <option value="{{ $kebersihan->id }}">{{ $kebersihan->tingkat_kebersihan }}</option>
                                        @endforeach
                                    </select>
                                    <p x-show="errors.kebersihan_id" x-text="errors.kebersihan_id" class="mt-1 text-xs text-red-600"></p>
                                </div>

                                <!-- Ventilasi Dropdown -->
                                <div class="w-full">
                                    <label for="ventilasi_id" class="block text-sm font-medium text-gray-700">Ventilasi <span class="text-red-500">*</span></label>
                                    <select id="ventilasi_id" name="ventilasi_id" x-model="formData.ventilasi_id" required
                                        class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                        <option value="" disabled selected>Pilih Kondisi Ventilasi</option>
                                        @foreach($facilities['ventilasi'] as $ventilasi)
                                            <option value="{{ $ventilasi->id }}">{{ $ventilasi->kondisi_ventilasi }}</option>
                                        @endforeach
                                    </select>
                                    <p x-show="errors.ventilasi_id" x-text="errors.ventilasi_id" class="mt-1 text-xs text-red-600"></p>
                                </div>

                                <!-- Keamanan Dropdown -->
                                <div class="w-full">
                                    <label for="keamanan_id" class="block text-sm font-medium text-gray-700">Tingkat Keamanan <span class="text-red-500">*</span></label>
                                    <select id="keamanan_id" name="keamanan_id" x-model="formData.keamanan_id" required
                                        class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                        <option value="" disabled selected>Pilih Tingkat Keamanan</option>
                                        @foreach($facilities['keamanan'] as $keamanan)
                                            <option value="{{ $keamanan->id }}">{{ $keamanan->tingkat_keamanan }}</option>
                                        @endforeach
                                    </select>
                                    <p x-show="errors.keamanan_id" x-text="errors.keamanan_id" class="mt-1 text-xs text-red-600"></p>
                                </div>

                                <!-- Iuran Dropdown -->
                                <div class="w-full">
                                    <label for="iuran_id" class="block text-sm font-medium text-gray-700">Iuran <span class="text-red-500">*</span></label>
                                    <select id="iuran_id" name="iuran_id" x-model="formData.iuran_id" required
                                        class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                        <option value="" disabled selected>Pilih Kategori Iuran</option>
                                        @foreach($facilities['iuran'] as $iuran)
                                            <option value="{{ $iuran->id }}">{{ $iuran->kategori }}</option>
                                        @endforeach
                                    </select>
                                    <p x-show="errors.iuran_id" x-text="errors.iuran_id" class="mt-1 text-xs text-red-600"></p>
                                </div>

                                <!-- Aturan Dropdown -->
                                <div class="w-full">
                                    <label for="aturan_id" class="block text-sm font-medium text-gray-700">Aturan Kost <span class="text-red-500">*</span></label>
                                    <select id="aturan_id" name="aturan_id" x-model="formData.aturan_id" required
                                        class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                        <option value="" disabled selected>Pilih Aturan Kost</option>
                                        @foreach($facilities['aturan'] as $aturan)
                                            <option value="{{ $aturan->id }}">{{ $aturan->jenis_aturan }}</option>
                                        @endforeach
                                    </select>
                                    <p x-show="errors.aturan_id" x-text="errors.aturan_id" class="mt-1 text-xs text-red-600"></p>
                                </div>

                                <!-- Deskripsi -->
                                <div class="w-full">
                                    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi Kosan <span class="text-red-500">*</span></label>
                                    <textarea id="description" name="description" x-model="formData.description" rows="4" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        placeholder="Berikan deskripsi singkat tentang kosan Anda"></textarea>
                                    <p x-show="errors.description" x-text="errors.description" class="mt-1 text-xs text-red-600"></p>
                                </div>

                                <!-- Upload Foto Utama -->
                                <div class="w-full">
                                    <label for="foto_utama" class="block text-sm font-medium text-gray-700">Foto Kosan <span class="text-red-500">*</span></label>
                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md" @drop.prevent="handleMainPhotoDrop($event)" @dragover.prevent>
                                        <div class="space-y-1 text-center" x-show="!mainPhotoPreview">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600 justify-center">
                                                <label for="foto_utama" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                    <span>Pilih foto utama</span>
                                                    <input id="foto_utama" name="foto_utama" type="file" accept="image/*" class="sr-only" @change="handleMainPhotoUpload($event)">
                                                </label>
                                                <p class="pl-1 px-2">atau drop file di sini</p>
                                            </div>
                                            <p class="text-xs text-gray-500">PNG, JPG, GIF hingga 5MB</p>
                                        </div>
                                        <div x-show="mainPhotoPreview" class="relative group w-full">
                                            <img :src="mainPhotoPreview" class="h-48 w-full object-cover rounded-md">
                                            <button type="button" @click="removeMainPhoto()" class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center opacity-75 hover:opacity-100 transition-opacity">
                                                &times;
                                            </button>
                                            <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-sm p-2 text-center">
                                                Foto Kosan
                                            </div>
                                        </div>
                                    </div>
                                    <p x-show="errors.foto_utama" x-text="errors.foto_utama" class="mt-1 text-sm text-red-600"></p>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    <!-- Navigation Buttons -->
                    <div class="pt-4 border-t border-gray-200 mt-6 flex flex-col-reverse sm:flex-row justify-between items-center gap-3 sm:gap-0">
                        <button 
                            type="button" 
                            x-show="currentStep > 1" 
                            @click="prevStep()"
                            class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150">
                            <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali
                        </button>
                        
                        <div class="w-full sm:w-auto flex flex-col sm:flex-row gap-3">
                            <div style="width: 100%;">
                                <!-- Simplified Button -->
                                <button 
                                    type="button" 
                                    x-show="currentStep < 3" 
                                    @click="nextStep()"
                                    style="
                                        width: 100%;
                                        display: inline-flex;
                                        justify-content: center;
                                        align-items: center;
                                        padding: 0.5rem 1.5rem;
                                        background-color:rgb(118, 48, 255);
                                        color: white !important;
                                        border: none;
                                        border-radius: 0.375rem;
                                        font-size: 0.875rem;
                                        line-height: 1.25rem;
                                        font-weight: 500;
                                    ">
                                    <span style="color: white !important; display: inline-block !important; opacity: 1 !important; margin-right: 0.5rem; font-size: 0.875rem;">
                                        Selanjutnya
                                    </span>
                                </button>
                            </div>
                            <div x-show="currentStep === 3" class="w-full mt-2">
                                <button 
                                    type="button" 
                                    @click="submitForm()"
                                    style="background-color: #059669 !important; color: white !important;"
                                    class="w-full flex justify-center items-center px-6 py-2 border border-transparent shadow-sm text-base font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-150"
                                    :disabled="isSubmitting"
                                    :class="{'opacity-75 cursor-not-allowed': isSubmitting}">
                                    <svg x-show="isSubmitting" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span style="color: white !important;" x-text="isSubmitting ? 'Menyimpan...' : 'Simpan Kosan'">Simpan</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush

@push('scripts')
<!-- Load jQuery first -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Then load Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
// Initialize Select2 when document is ready
document.addEventListener('DOMContentLoaded', function() {
    if (typeof $ !== 'undefined' && $.fn.select2) {
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Pilih...',
            allowClear: true,
            language: {
                noResults: function() {
                    return 'Tidak ada hasil yang ditemukan';
                },
                searching: function() {
                    return 'Mencari...';
                },
                inputTooShort: function(args) {
                    return 'Masukkan minimal ' + args.minimum + ' karakter';
                }
            }
        });
    }
});

// Main component script
document.addEventListener('alpine:init', () => {
    // Define initial form data structure in one place
    const initialFormData = {
        nama_kosan: '',
        alamat: '',
        deskripsi: '',
        harga: '',
        jarak_kampus: '',
        luas_kamar: '',
        tipe_kost: 'Campur',
        jumlah_kamar_tersedia: 1,
        keamanan_id: '',
        kebersihan_id: '',
        ventilasi_id: '',
        iuran_id: '',
        aturan_id: '',
        fasilitas_kamar: [],
        fasilitas_kamar_mandi: [],
        fasilitas_umum: [],
        akses_lokasi_pendukung: [],
        foto_utama: null,
        foto: []
    };

    Alpine.data('addKosanModal', () => ({
        // Component state
        isOpen: false,
        currentStep: 1,
        isSubmitting: false,
        errors: {},
        mainPhotoPreview: null,
        mainPhotoFile: null,
        
        // Initialize form data from our single source of truth
        formData: JSON.parse(JSON.stringify(initialFormData)),

        // Clear error for a specific field
        clearError(field) {
            if (this.errors[field]) {
                this.errors[field] = null;
                // Force Alpine to detect the change
                this.errors = {...this.errors};
            }
        },
        
        // Initialize component
        init() {
            // Listen for the open-add-kosan-modal event
            window.addEventListener('open-add-kosan-modal', () => {
                this.open();
            });
            
            // Close modal when clicking outside
            this.$watch('isOpen', value => {
                if (value) {
                    document.body.classList.add('overflow-hidden');
                    this.$nextTick(() => {
                        if (this.$refs.dialog) {
                            this.$refs.dialog.focus();
                        }
                    });
                } else {
                    document.body.classList.remove('overflow-hidden');
                }
            });
        },

        // Reset form to initial state
        resetForm() {
            this.formData = JSON.parse(JSON.stringify(initialFormData));
            this.mainPhotoPreview = null;
            this.mainPhotoFile = null;
            this.errors = {};
            this.currentStep = 1;
        },

        // Open the modal
        open() {
            this.isOpen = true;
            this.resetForm();
            this.$nextTick(() => {
                if (this.$refs.dialog) {
                    this.$refs.dialog.scrollTop = 0;
                }
            });
        },

        // Close the modal
        close() {
            this.isOpen = false;
            this.resetForm();
            document.body.classList.remove('overflow-hidden');
        },

        // Navigation methods
        nextStep() {
            if (this.validateStep(this.currentStep)) {
                if (this.currentStep < 3) {
                    this.currentStep++;
                }
            }
        },
        
        prevStep() {
            if (this.currentStep > 1) {
                this.currentStep--;
            }
        },

        // Validate current step
        validateStep(step) {
            console.log('Validating step:', step);
            this.errors = {};
            let isValid = true;
            
            const addError = (field, message) => {
                this.errors[field] = message;
                isValid = false;
            };
            
            if (step === 1) {
                // Reset errors
                this.errors = {};
                
                // Validate nama kosan
                if (!this.formData.nama_kosan || this.formData.nama_kosan.trim() === '') {
                    this.errors.nama_kosan = 'Nama kosan wajib diisi';
                    isValid = false;
                }
                
                // Validate alamat
                if (!this.formData.alamat || this.formData.alamat.trim() === '') {
                    this.errors.alamat = 'Alamat wajib diisi';
                    isValid = false;
                }
                
                // Validate harga
                if (!this.formData.harga || isNaN(this.formData.harga) || parseFloat(this.formData.harga) < 100000) {
                    this.errors.harga = 'Harga minimal Rp 100.000';
                    isValid = false;
                }
                
                // Validate luas kamar
                if (!this.formData.luas_kamar || isNaN(this.formData.luas_kamar) || parseFloat(this.formData.luas_kamar) < 9) {
                    this.errors.luas_kamar = 'Luas kamar minimal 9 m²';
                    isValid = false;
                }
                
                // Validate jarak kampus
                if (!this.formData.jarak_kampus || isNaN(this.formData.jarak_kampus) || 
                    parseFloat(this.formData.jarak_kampus) < 0.1 || parseFloat(this.formData.jarak_kampus) > 20) {
                    this.errors.jarak_kampus = 'Jarak ke kampus harus antara 0.1 - 20 km';
                    isValid = false;
                }
                
                // Validate jumlah kamar tersedia
                if (!this.formData.jumlah_kamar_tersedia || isNaN(this.formData.jumlah_kamar_tersedia) || 
                    parseInt(this.formData.jumlah_kamar_tersedia) < 1 || parseInt(this.formData.jumlah_kamar_tersedia) > 100) {
                    this.errors.jumlah_kamar_tersedia = 'Jumlah kamar tersedia harus 1-100';
                    isValid = false;
                }
                
                // Validate kost type
                if (!this.formData.tipe_kost) {
                    addError('tipe_kost', 'Tipe kost wajib dipilih');
                }
            } else if (step === 2) {
                // Validate at least one facility is selected in each category
                if (this.formData.fasilitas_kamar.length === 0) {
                    addError('fasilitas_kamar', 'Pilih minimal 1 fasilitas kamar');
                }
                
                if (this.formData.fasilitas_kamar_mandi.length === 0) {
                    addError('fasilitas_kamar_mandi', 'Pilih minimal 1 fasilitas kamar mandi');
                }
                
                if (this.formData.fasilitas_umum.length === 0) {
                    addError('fasilitas_umum', 'Pilih minimal 1 fasilitas umum');
                }
            } else if (step === 3) {
                // Validate all required fields are filled
                const requiredFields = [
                    'kebersihan_id', 'ventilasi_id', 'iuran_id', 'aturan_id', 'keamanan_id'
                ];
                
                requiredFields.forEach(field => {
                    if (!this.formData[field]) {
                        addError(field, 'Field ini wajib diisi');
                    }
                });
                
                // Validate description
                if (!this.formData.description || this.formData.description.trim() === '') {
                    addError('description', 'Deskripsi wajib diisi');
                }
                
                // Validate main photo
                if (!this.mainPhotoFile) {
                    addError('foto_utama', 'Foto utama wajib diunggah');
                }
            }
            
            return isValid;
        },

        // Handle main photo upload via file input
        handleMainPhotoUpload(event) {
            const file = event.target.files[0];
            if (!file || !file.type.match('image.*')) {
                this.showToast('error', 'Error', 'File harus berupa gambar (JPG, PNG, GIF)');
                return;
            }
            
            // Check file size (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                this.showToast('error', 'Error', 'Ukuran file maksimal 5MB');
                return;
            }
            
            const reader = new FileReader();
            reader.onload = (e) => {
                this.mainPhotoPreview = e.target.result;
                this.mainPhotoFile = file;
            };
            reader.onerror = () => {
                this.showToast('error', 'Error', 'Gagal memuat gambar');
            };
            reader.readAsDataURL(file);
            
            // Clear any previous error
            if (this.errors.foto_utama) {
                this.errors.foto_utama = null;
            }
        },
        
        // Submit form
        async submitForm() {
            if (!this.validateStep(3)) {
                return;
            }
            
            if (!this.mainPhotoFile) {
                this.errors.foto_utama = 'Foto kosan wajib diunggah';
                this.scrollToError('foto_utama');
                return;
            }
            
            const form = document.getElementById('addKosanForm');
            const formData = new FormData(form);
            
            // Add CSRF token if not already in form
            if (!formData.has('_token')) {
                formData.append('_token', '{{ csrf_token() }}');
            }
            
            // Add main photo
            if (this.mainPhotoFile) {
                formData.set('foto_utama', this.mainPhotoFile);
            }
            
            // Show loading state
            this.isSubmitting = true;
            
            try {
                const response = await fetch('{{ route("kosan.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                const result = await response.json();
                
                if (!response.ok) {
                    if (result.errors) {
                        this.errors = result.errors;
                        const firstError = Object.keys(result.errors)[0];
                        if (firstError) this.scrollToError(firstError);
                    }
                    throw new Error(result.message || 'Terjadi kesalahan saat menyimpan data');
                }
                
                // Show success message
                this.showToast('success', 'Sukses', 'Data kosan berhasil disimpan');
                
                // Reset form and close modal
                this.resetForm();
                this.isOpen = false;  // Changed from showModal to isOpen
                
                // Optional: You can add a success message or update the UI here
                // For example, you could emit an event that the parent component can listen to
                this.$dispatch('kosan-added');
                
            } catch (error) {
                console.error('Error submitting form:', error);
                this.showToast('error', 'Error', error.message || 'Terjadi kesalahan saat mengirim data');
            } finally {
                this.isSubmitting = false;
            }
        },
        
        // Scroll to the first error field
        scrollToError(fieldName) {
            this.$nextTick(() => {
                const element = document.querySelector(`[name="${fieldName}"]`);
                if (element) {
                    element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    element.focus();
                }
            });
        },
        
        // Show toast notification
        showToast(type, title, message) {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg text-white ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            }`;
            
            toast.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <div>
                        <p class="font-semibold">${title}</p>
                        <p class="text-sm">${message}</p>
                    </div>
                    <button type="button" class="ml-4 text-white hover:text-gray-200 focus:outline-none" @click="this.parentElement.parentElement.remove()">
                        <span class="sr-only">Tutup</span>
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                if (document.body.contains(toast)) {
                    toast.remove();
                }
            }, 5000);
        }
    }));
});
</script>
@endpush
