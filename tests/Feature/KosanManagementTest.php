<?php

namespace Tests\Feature;

use App\Models\Kosan;
use App\Models\User;
use App\Models\FasilitasKamar;
use App\Models\FasilitasKamarMandi;
use App\Models\FasilitasUmum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class KosanManagementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Enable query logging
        \DB::enableQueryLog();
        
        // Run necessary seeders
        $this->seed([
            \Database\Seeders\UsersTableSeeder::class,
            \Database\Seeders\SupportingTablesSeeder::class,
            \Database\Seeders\FacilitiesSeeder::class,
        ]);
        
        // Get the first user and authenticate
        $this->user = User::first();
        $this->actingAs($this->user);
        
        // Fake the storage
        Storage::fake('public');
        
        // Clear the query log
        \DB::flushQueryLog();
    }
    
    protected function tearDown(): void
    {
        parent::tearDown();
        
        // Clean up storage
        Storage::disk('public')->deleteDirectory('kosan');
    }

    /** @test */
    public function it_can_create_a_kosan_with_main_photo()
    {
        // Disable exception handling to see the actual error
        $this->withoutExceptionHandling();
        
        // Get the first available facility IDs
        $fasilitasKamar = \App\Models\FasilitasKamar::take(2)->pluck('id')->toArray();
        $fasilitasKamarMandi = [\App\Models\FasilitasKamarMandi::first()->id];
        $fasilitasUmum = \App\Models\FasilitasUmum::take(3)->pluck('id')->toArray();
        
        // Create test data with all required fields
        $data = [
            'name' => 'Test Kosan',
            'address' => 'Jl. Test No. 123',
            'description' => $this->faker->paragraph,
            'price_per_month' => '1500000',
            'distance_to_campus' => '1.5',
            'room_size' => '16',
            'jumlah_kamar_tersedia' => '5',
            'tipe_kost' => 'Putri',
            'keamanan_id' => '1',
            'kebersihan_id' => '1',
            'ventilasi_id' => '1',
            'iuran_id' => '1',
            'aturan_id' => '1',
            'fasilitas_kamar' => $fasilitasKamar,
            'fasilitas_kamar_mandi' => $fasilitasKamarMandi,
            'fasilitas_umum' => $fasilitasUmum,
            'foto' => [
                UploadedFile::fake()->create('photo1.jpg', 100, 'image/jpeg'),
                UploadedFile::fake()->create('photo2.jpg', 100, 'image/jpeg')
            ]
        ];

        // Create a test file for the main photo
        $mainPhoto = UploadedFile::fake()->create('main.jpg', 100, 'image/jpeg');
        
        // Create the request with files
        $response = $this->post(route('kosan.store'), array_merge($data, [
            'foto_utama' => $mainPhoto,
        ]));
        
        // Dump the response for debugging
        if ($response->status() !== 200) {
            dump([
                'status' => $response->status(),
                'content' => $response->getContent(),
                'errors' => $response->exception?->getMessage(),
                'queries' => \DB::getQueryLog(),
                'response_content' => $response->getContent(),
                'response_headers' => $response->headers->all(),
            ]);
            
            // Also log the exception if it exists
            if ($response->exception) {
                dump([
                    'exception' => [
                        'message' => $response->exception->getMessage(),
                        'file' => $response->exception->getFile(),
                        'line' => $response->exception->getLine(),
                        'trace' => $response->exception->getTraceAsString(),
                    ]
                ]);
            }
        }

        // Assert response
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Kosan berhasil ditambahkan!'
            ]);

        // Assert database has the kosan
        $this->assertDatabaseHas('kosan', [
            'nama' => 'Test Kosan',
            'harga' => 1500000,
            'jumlah_kamar_tersedia' => 5,
        ]);

        // Get the created kosan
        $kosan = Kosan::where('nama', 'Test Kosan')->first();
        
        // Assert relationships - use the correct relationship method names
        $this->assertCount(2, $kosan->fasilitasKamar);
        $this->assertCount(1, $kosan->fasilitasKamarMandi);
        $this->assertCount(3, $kosan->fasilitasUmum);
        
        // Assert the main photo was set
        $this->assertNotNull($kosan->foto_utama);
        
        // Get the relative path without the 'storage/' prefix
        $relativePath = str_replace('storage/', '', $kosan->foto_utama);
        
        // Assert the file exists in the storage
        $this->assertTrue(
            Storage::disk('public')->exists($relativePath),
            "The file [{$relativePath}] does not exist in storage. " .
            "Full path: " . storage_path('app/public/' . $relativePath)
        );
        
        // Assert additional photos were saved
        $this->assertGreaterThan(0, $kosan->foto->count(), 'No additional photos were saved');
        
        // Assert each photo exists in storage
        foreach ($kosan->foto as $foto) {
            $relativePath = str_replace('storage/', '', $foto->path);
            $this->assertTrue(
                Storage::disk('public')->exists($relativePath),
                "The file [{$relativePath}] does not exist in storage"
            );
        }
    }

    /** @test */
    public function it_requires_valid_data()
    {
        $response = $this->postJson(route('kosan.store'), []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'name',
                'address',
                'description',
                'price_per_month',
                'distance_to_campus',
                'room_size',
                'tipe_kost',
                'jumlah_kamar_tersedia',
                'keamanan_id',
                'kebersihan_id',
                'ventilasi_id',
                'iuran_id',
                'aturan_id',
                'foto',
                'foto_utama'
            ]);
    }

    /** @test */
    public function it_uses_default_image_when_no_photos_provided()
    {
        // This test would require mocking the default image creation
        $this->markTestIncomplete('Need to implement default image test');
    }
}
