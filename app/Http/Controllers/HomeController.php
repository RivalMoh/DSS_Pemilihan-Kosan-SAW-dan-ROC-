<?php

namespace App\Http\Controllers;

use App\Models\Kosan;
use App\Models\LuasKamar;
use App\Models\HargaSewa;
use App\Models\EstimasiJarak;
use App\Models\Keamanan;
use App\Models\Kebersihan;
use App\Models\Iuran;
use App\Models\Ventilasi;
use App\Models\Aturan;
use App\Models\FasilitasKamar;
use App\Models\FasilitasKamarMandi;
use App\Models\FasilitasUmum;
use App\Http\Requests\StoreKosanRequest;
use App\Services\KosanService;
use App\Services\KosanRecommendationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    protected $kosanService;
    protected $recommendationService;

    public function __construct(KosanService $kosanService, KosanRecommendationService $recommendationService)
    {
        $this->kosanService = $kosanService;
        $this->recommendationService = $recommendationService;
    }
    /**
     * Show the application home page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Base query with relationships
        $query = Kosan::with([
            'keamanan',
            'kebersihan', 
            'iuran',
            'aturan',
            'ventilasi',
            'fasilitas_kamar',
            'fasilitas_kamar_mandi',
            'fasilitas_umum',
            'foto'
        ]);

        // If on dashboard, only show user's kosan
        if (request()->is('dashboard*')) {
            $query->where('user_id', auth()->id());
            $kosan = $query->paginate(9);
            $facilities = $this->getFacilities();
            $luasKamar = LuasKamar::orderBy('min_value')->get();
            
            return view('home', compact('kosan', 'facilities', 'luasKamar'));
        }
        
        // For home page, show recommendations with default preferences
        $defaultPreferences = [
            'harga' => 1000000,
            'luas_kamar' => 20,
            'jarak_kampus' => 2.5,
            'keamanan' => ['pagar_tinggi', 'cctv'],
            'kebersihan' => 'bersih',
            'iuran' => ['listrik', 'air'],
            'fasilitas_kamar' => ['ac', 'lemari', 'meja', 'kursi', 'wifi'],
            'fasilitas_kamar_mandi' => ['air_bersih', 'air_panas', 'kloset_duduk'],
            'fasilitas_umum' => ['parkir_motor', 'parkir_mobil', 'dapur', 'ruang_tamu'],
            'akses_lokasi_pendukung' => ['warung_makan', 'minimarket', 'atm', 'halte_bus']
        ];
        
        $recommendedKosan = $this->recommendationService->calculateRecommendations($defaultPreferences);
        
        // Get all kosan UniqueIDs in recommended order
        $kosanIds = $recommendedKosan->pluck('kosan.UniqueID')->filter()->toArray();
        
        if (!empty($kosanIds)) {
            // Use the correct primary key column name 'UniqueID'
            $query->whereIn('UniqueID', $kosanIds);
            
            // Only add order by if we have IDs to order by
            if (!empty($kosanIds)) {
                $placeholders = implode(',', array_fill(0, count($kosanIds), '?'));
                $query->orderByRaw("FIELD(UniqueID, $placeholders)", $kosanIds);
            }
        }
        
        // Get room sizes for filter
        $luasKamar = LuasKamar::orderBy('min_value')->get();
        
        // Return view with recommended kosan data
        $kosan = $query->paginate(9);
        $facilities = $this->getFacilities();
        
        return view('home', compact('kosan', 'facilities', 'recommendedKosan', 'luasKamar'));
    }
    
    /**
     * Get facilities for filter
     * 
     * @return array
     */
    protected function getFacilities(): array
    {
        return [
            'kamar' => FasilitasKamar::orderBy('nama_fasilitas')->get(),
            'kamar_mandi' => FasilitasKamarMandi::orderBy('nama_fasilitas')->get(),
            'umum' => FasilitasUmum::orderBy('nama_fasilitas')->get(),
            'akses_lokasi' => \App\Models\AksesLokasiPendukung::orderBy('nama_lokasi')->get(),
            'kebersihan' => \App\Models\Kebersihan::orderBy('nilai', 'desc')->get(),
            'ventilasi' => \App\Models\Ventilasi::orderBy('nilai', 'desc')->get(),
            'keamanan' => \App\Models\Keamanan::orderBy('nilai', 'desc')->get(),
            'iuran' => \App\Models\Iuran::orderBy('nilai', 'asc')->get(),
            'aturan' => \App\Models\Aturan::orderBy('nilai', 'asc')->get(),
            'tipe_kost' => [
                'Putri',
                'Putra',
                'Campur'
            ]
        ];
    }
    
    /**
     * Show the form for creating a new kosan.
     *
     * @return \Illuminate\View\View
     */
    /**
     * Display the specified kosan.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $kosan = Kosan::with([
                'keamanan',
                'kebersihan',
                'iuran',
                'aturan',
                'ventilasi',
                'fasilitas_kamar',
                'fasilitas_kamar_mandi',
                'fasilitas_umum',
                'akses_lokasi_pendukung',
                'user' => function($query) {
                    $query->select('id', 'name', 'no_hp');
                },
                'foto'
            ])
            ->select('kosan.*')
            ->where('UniqueID', $id)
            ->firstOrFail();
            
        // Get similar kosan (same area based on first part of address)
        $area = explode(',', $kosan->alamat)[0]; // Get first part of address before the first comma
        $similarKosan = Kosan::where('alamat', 'like', $area . '%')
            ->where('UniqueID', '!=', $kosan->UniqueID)
            ->take(4)
            ->get();
            
        return view('kosan.show', [
            'kosan' => $kosan,
            'similarKosan' => $similarKosan
        ]);
    }
    
    /**
     * Show the form for creating a new kosan.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Get all facilities for the form
        $fasilitasKamar = FasilitasKamar::all();
        $fasilitasKamarMandi = FasilitasKamarMandi::all();
        $fasilitasUmum = FasilitasUmum::all();
        
        return view('kosan.create', [
            'fasilitasKamar' => $fasilitasKamar,
            'fasilitasKamarMandi' => $fasilitasKamarMandi,
            'fasilitasUmum' => $fasilitasUmum,
            'keamanan' => Keamanan::orderBy('nilai', 'desc')->get(),
            'kebersihan' => Kebersihan::orderBy('nilai', 'desc')->get(),
            'iuran' => Iuran::orderBy('nilai', 'desc')->get(),
            'ventilasi' => Ventilasi::orderBy('nilai', 'desc')->get(),
            'aturan' => Aturan::orderBy('nilai', 'desc')->get(),
            'tipe_kost' => ['Putri', 'Putra', 'Campur']
        ]);
    }

    /**
     * Store a newly created kosan in storage.
     *
     * @param  StoreKosanRequest  $request
     * @return JsonResponse
     */
    public function store(StoreKosanRequest $request)
    {
        \Log::info('Form submission started', ['request_data' => $request->all()]);
        \Log::info('Files in request:', ['files' => $request->allFiles()]);
        \Log::info('Validated data:', $request->validated());
        
        DB::beginTransaction();
        
        try {
            // Debug log the incoming request data (excluding files)
            \Log::info('=== KOSAN STORE REQUEST ===');
            \Log::info('Form data received:', $request->except(['foto_utama', 'foto']));
            \Log::info('Has file foto_utama:', ['has_file' => $request->hasFile('foto_utama')]);
            
            // Validate the request (handled by StoreKosanRequest)
            $validated = $request->validated();
            
            // Add the authenticated user's ID to the validated data
            $validated['user_id'] = auth()->id();
            
            // Extract facility arrays - ensure they are arrays
            $facilities = [
                'fasilitas_kamar' => is_array($request->input('fasilitas_kamar', [])) ? 
                    array_map('intval', $request->input('fasilitas_kamar')) : [],
                'fasilitas_kamar_mandi' => is_array($request->input('fasilitas_kamar_mandi', [])) ? 
                    array_map('intval', $request->input('fasilitas_kamar_mandi')) : [],
                'fasilitas_umum' => is_array($request->input('fasilitas_umum', [])) ? 
                    array_map('intval', $request->input('fasilitas_umum')) : [],
                'akses_lokasi_pendukung' => is_array($request->input('akses_lokasi_pendukung', [])) ? 
                    array_map('intval', $request->input('akses_lokasi_pendukung')) : [],
            ];
            
            // Log the facilities data for debugging
            \Log::info('Facilities data to be saved:', $facilities);
            
            // Debug log the facilities
            \Log::info('Facilities to be attached:', $facilities);
            
            // Handle file upload
            if (!$request->hasFile('foto_utama')) {
                throw new \Exception('Foto utama kosan tidak ditemukan');
            }
            
            $file = $request->file('foto_utama');
            $path = $file->store('kosan_photos', 'public');
            
            if (!$path) {
                throw new \Exception('Gagal mengunggah foto utama');
            }
            
            // Generate a unique ID for the kosan
            $uniqueId = 'KSN' . time() . strtoupper(substr(uniqid(), -6));
            
            // Create kosan record with the validated data
            $kosanData = [
                'UniqueID' => $uniqueId,
                'user_id' => $validated['user_id'],
                'nama' => $validated['nama_kosan'],  // Map to 'nama' in database
                'alamat' => $validated['alamat'],
                'deskripsi' => $validated['description'] ?? 'Tidak ada deskripsi',
                'harga' => $validated['harga'] ?? 0,
                'luas_kamar' => $validated['luas_kamar'] ?? 0,
                'jumlah_kamar_tersedia' => $validated['jumlah_kamar_tersedia'] ?? 1,
                'tipe_kost' => $validated['tipe_kost'] ?? 'Campur',
                'Jarak_kampus' => $validated['jarak_kampus'] ?? 0,  // Note the capital 'J' in Jarak_kampus
                'keamanan_id' => $validated['keamanan_id'],
                'kebersihan_id' => $validated['kebersihan_id'],
                'ventilasi_id' => $validated['ventilasi_id'],
                'iuran_id' => $validated['iuran_id'],
                'aturan_id' => $validated['aturan_id'],
                'foto_utama' => $path,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            // Debug log the kosan data
            \Log::info('Creating kosan with data:', $kosanData);
            
            // Create the kosan
            $kosan = Kosan::create($kosanData);
            
            if (!$kosan) {
                throw new \Exception('Gagal membuat data kosan');
            }
            
            // Attach facilities
            if (!empty($facilities['fasilitas_kamar'])) {
                $kosan->fasilitas_kamar()->attach($facilities['fasilitas_kamar']);
            }
            if (!empty($facilities['fasilitas_kamar_mandi'])) {
                $kosan->fasilitas_kamar_mandi()->attach($facilities['fasilitas_kamar_mandi']);
            }
            if (!empty($facilities['fasilitas_umum'])) {
                $kosan->fasilitas_umum()->attach($facilities['fasilitas_umum']);
            }
            if (!empty($facilities['akses_lokasi_pendukung'])) {
                // For akses_lokasi_pendukung, we'll add order and count to the pivot
                $aksesWithPivot = [];
                foreach ($facilities['akses_lokasi_pendukung'] as $index => $aksesId) {
                    $aksesWithPivot[$aksesId] = [
                        'order' => $index + 1,
                        'count' => 1, // Default count is 1, can be updated later
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                $kosan->akses_lokasi_pendukung()->attach($aksesWithPivot);
            }
            
            // Commit the transaction
            DB::commit();
            \Log::info('Kosan created successfully with ID: ' . $kosan->id);
            \Log::info('Kosan created successfully', ['kosan_id' => $kosan->id]);
            
            return response()->json([
                'success' => true,
                'message' => 'Kosan berhasil ditambahkan!',
                'redirect' => route('dashboard')
            ]);
                
        } catch (\Exception $e) {
            // Rollback transaction if not already done
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            
            // Log the error
            \Log::error('Error in kosan store:');
            \Log::error('Error: ' . $e->getMessage());
            \Log::error('File: ' . $e->getFile() . ':' . $e->getLine());
            \Log::error('Trace: ' . $e->getTraceAsString());
            \Log::error('Request data: ' . json_encode($request->except(['foto_utama', 'foto'])));
            \Log::error('=== END KOSAN STORE REQUEST WITH ERROR ===');
            
            // Return error response
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . ($e->getMessage() ?: 'Tidak dapat memproses permintaan'),
                'error' => config('app.debug') ? [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ] : null
            ], 500);
        }
    }
    
    /**
     * Show the form for editing the specified kosan.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $kosan = Kosan::with([
            'keamanan',
            'kebersihan',
            'iuran',
            'aturan',
            'ventilasi',
            'fasilitas_kamar',
            'fasilitas_kamar_mandi',
            'fasilitas_umum',
            'akses_lokasi_pendukung',
            'user',
            'foto'
        ])->findOrFail($id);

        // Get all necessary data for the edit form
        $luas = \App\Models\LuasKamar::orderBy('min_value')->get();
        $fasilitas = \App\Models\Fasilitas::all();
        $tipe = \App\Models\TipoKos::all();
        
        return view('admin.index.edit-kos', [
            'kos' => $kosan,
            'luas' => $luas,
            'fasilitas' => $fasilitas,
            'tipe' => $tipe,
        ]);
    }

    public function destroy(string $id)
    {
        //
    }
}
