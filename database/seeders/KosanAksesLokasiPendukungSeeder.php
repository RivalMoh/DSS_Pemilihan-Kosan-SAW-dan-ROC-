<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kosan;
use App\Models\AksesLokasiPendukung;
use Illuminate\Support\Facades\DB;

class KosanAksesLokasiPendukungSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Truncate the pivot table
        DB::table('kosan_akses_lokasi_pendukung')->truncate();
        
        // Get all kosan and locations
        $kosans = Kosan::all();
        $locations = AksesLokasiPendukung::all();
        
        if ($kosans->isEmpty() || $locations->isEmpty()) {
            $this->command->warn('No kosans or locations found. Skipping pivot table seeding.');
            return;
        }
        
        $pivotData = [];
        $now = now();
        
        foreach ($kosans as $kosan) {
            // Randomly select 2-4 locations for each kosan
            $selectedLocations = $locations->random(rand(2, min(4, $locations->count())));
            
            foreach ($selectedLocations as $index => $location) {
                $pivotData[] = [
                    'kosan_id' => $kosan->UniqueID,
                    'akses_lokasi_pendukung_id' => $location->id,
                    'count' => rand(1, 3), // Random count between 1-3
                    'order' => $index + 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }
        
        // Insert in chunks to avoid memory issues with large datasets
        foreach (array_chunk($pivotData, 100) as $chunk) {
            DB::table('kosan_akses_lokasi_pendukung')->insert($chunk);
        }
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $this->command->info('Kosan Akses Lokasi Pendukung pivot table seeded successfully!');
    }
}
