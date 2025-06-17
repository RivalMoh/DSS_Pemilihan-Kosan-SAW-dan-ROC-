<?php

namespace Database\Seeders;

use App\Models\Kosan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KosanFacilityPivotSeeder extends Seeder
{
    public function run(): void
    {
        // Get all kosan IDs
        $kosanIds = Kosan::pluck('UniqueID');
        
        // Skip if no kosan exists
        if ($kosanIds->isEmpty()) {
            $this->command->info('No kosan found. Please run KosanSeeder first.');
            return;
        }

        // Get all facility IDs
        $fasilitasKamarIds = DB::table('fasilitas_kamar')->pluck('id');
        $fasilitasKamarMandiIds = DB::table('fasilitas_kamar_mandi')->pluck('id');
        $fasilitasUmumIds = DB::table('fasilitas_umum')->pluck('id');

        // Skip if no facilities exist
        if ($fasilitasKamarIds->isEmpty() || $fasilitasKamarMandiIds->isEmpty() || $fasilitasUmumIds->isEmpty()) {
            $this->command->info('No facilities found. Please run FacilitiesSeeder first.');
            return;
        }

        // Prepare data for kosan_fasilitas_kamar
        $kosanFasilitasKamar = [];
        foreach ($kosanIds as $kosanId) {
            // Randomly select 2-4 facilities for each kosan
            $count = min(4, $fasilitasKamarIds->count());
            $selectedFasilitasKamar = $fasilitasKamarIds->random(rand(2, $count));
            
            $order = 1; // Reset order counter for each kosan
            foreach ($selectedFasilitasKamar as $fasilitasId) {
                $kosanFasilitasKamar[] = [
                    'kosan_id' => $kosanId,
                    'fasilitas_kamar_id' => $fasilitasId,
                    'order' => $order++,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }


        // Prepare data for kosan_fasilitas_kamar_mandi
        $kosanFasilitasKamarMandi = [];
        foreach ($kosanIds as $kosanId) {
            // Randomly select 1-3 facilities for each kosan
            $count = min(3, $fasilitasKamarMandiIds->count());
            $selectedFasilitasKamarMandi = $fasilitasKamarMandiIds->random(rand(1, $count));
            
            $order = 1; // Reset order counter for each kosan
            foreach ($selectedFasilitasKamarMandi as $fasilitasId) {
                $kosanFasilitasKamarMandi[] = [
                    'kosan_id' => $kosanId,
                    'fasilitas_kamar_mandi_id' => $fasilitasId,
                    'order' => $order++,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Prepare data for kosan_fasilitas_umum
        $kosanFasilitasUmum = [];
        foreach ($kosanIds as $kosanId) {
            // Randomly select 3-6 facilities for each kosan
            $count = min(6, $fasilitasUmumIds->count());
            $selectedFasilitasUmum = $fasilitasUmumIds->random(rand(3, $count));
            
            $order = 1; // Reset order counter for each kosan
            foreach ($selectedFasilitasUmum as $fasilitasId) {
                $kosanFasilitasUmum[] = [
                    'kosan_id' => $kosanId,
                    'fasilitas_umum_id' => $fasilitasId,
                    'order' => $order++,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert data into junction tables
        if (!empty($kosanFasilitasKamar)) {
            DB::table('kosan_fasilitas_kamar')->insert($kosanFasilitasKamar);
        }
        
        if (!empty($kosanFasilitasKamarMandi)) {
            DB::table('kosan_fasilitas_kamar_mandi')->insert($kosanFasilitasKamarMandi);
        }
        
        if (!empty($kosanFasilitasUmum)) {
            DB::table('kosan_fasilitas_umum')->insert($kosanFasilitasUmum);
        }
    }
}
