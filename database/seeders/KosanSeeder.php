<?php

namespace Database\Seeders;

use App\Models\Kosan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KosanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure storage directory exists
        if (!Storage::disk('public')->exists('kosan')) {
            Storage::disk('public')->makeDirectory('kosan');
        }

        // Get required foreign key IDs (assuming these exist from other seeders)
        $keamananId = \App\Models\Keamanan::firstOrFail()->id;
        $kebersihanId = \App\Models\Kebersihan::firstOrFail()->id;
        $iuranId = \App\Models\Iuran::firstOrFail()->id;
        $aturanId = \App\Models\Aturan::firstOrFail()->id;
        $ventilasiId = \App\Models\Ventilasi::firstOrFail()->id;

        // Create sample kosan data
        $kosan = [];
        for ($i = 1; $i <= 10; $i++) {
            $kosan[] = Kosan::create([
                'UniqueID' => 'KSN' . strtoupper(Str::random(10)),
                'nama' => 'Kost Example ' . $i,
                'alamat' => 'Jl. Example No.' . $i . ', Kota Example',
                'harga' => rand(500000, 5000000),
                'luas_kamar' => rand(12, 40),
                'Jarak_kampus' => rand(1, 10) + (rand(0, 99) / 100),
                'keamanan_id' => $keamananId,
                'kebersihan_id' => $kebersihanId,
                'iuran_id' => $iuranId,
                'aturan_id' => $aturanId,
                'ventilasi_id' => $ventilasiId,
                'deskripsi' => 'Kost nyaman dengan fasilitas lengkap',
                'foto_utama' => 'default.jpg', // You can add a default image path if needed
                'jumlah_kamar_tersedia' => rand(1, 10),
                'tipe_kost' => ['Putri', 'Putra', 'Campur'][rand(0, 2)],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Since we removed junction tables, we don't need to attach facilities
        // If you need to work with facilities in the future, you can query them directly
        // For example:
        // $kamarFacilities = \App\Models\FasilitasKamar::all();
        // $kamarMandiFacilities = \App\Models\FasilitasKamarMandi::all();
        // $umumFacilities = \App\Models\FasilitasUmum::all();

        // Add sample photos if needed
        foreach ($kosan as $kost) {
            $photos = [];
            for ($i = 0; $i < rand(1, 5); $i++) {
                $photos[] = [
                    'path' => 'storage/kosan/sample-' . rand(1, 10) . '.jpg',
                    'is_primary' => $i === 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            // Only create photos if the relationship exists
            if (method_exists($kost, 'foto')) {
                $kost->foto()->createMany($photos);
            }
        }
    }
}
