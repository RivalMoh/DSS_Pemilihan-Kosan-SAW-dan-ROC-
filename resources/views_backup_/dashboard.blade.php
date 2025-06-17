<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Header and Add Button -->
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-900">Daftar Kosan Saya</h2>
                <x-add-kosan-modal :facilities="$facilities" />
            </div>

            <!-- Kosan List -->
            @if($kosan->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($kosan as $kost)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $kost->name }}</h3>
                                        <p class="text-sm text-gray-500">{{ $kost->address }}</p>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $kost->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $kost->is_available ? 'Tersedia' : 'Tidak Tersedia' }}
                                    </span>
                                </div>
                                
                                <div class="mt-4 flex items-center justify-between">
                                    <div>
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Harga:</span> Rp{{ number_format($kost->harga_sewa, 0, ',', '.') }}/bulan
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Kamar Tersedia:</span> {{ $kost->jumlah_kamar_tersedia }}
                                        </p>
                                    </div>
                                    <a href="{{ route('kosan.show', $kost->id) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">Lihat Detail</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $kosan->links() }}
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada kosan</h3>
                        <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan kosan baru.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Add Kosan Modal -->
    <x-add-kosan-modal :facilities="$facilities" :showButton="false" />
</x-app-layout>
