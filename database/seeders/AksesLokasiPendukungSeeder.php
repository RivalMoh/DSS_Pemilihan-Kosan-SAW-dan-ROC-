<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AksesLokasiPendukung;
use Illuminate\Support\Facades\DB;

class AksesLokasiPendukungSeeder extends Seeder
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
        
        // Truncate the table first
        AksesLokasiPendukung::truncate();
        
        // Define the data
        $locations = [
            [
                'nama_lokasi' => 'Transportasi Umum',
                'bobot' => 1,
                'keterangan' => 'Akses transportasi umum',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_lokasi' => 'Tempat Ibadah',
                'bobot' => 2,
                'keterangan' => 'Akses ke masjid, gereja, dll',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_lokasi' => 'Layanan Kesehatan',
                'bobot' => 3,
                'keterangan' => 'Akses ke fasilitas kesehatan (Rumah Sakit, Apotek, dan fasilitas kesehatan lainnya)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_lokasi' => 'Minimarket',
                'bobot' => 4,
                'keterangan' => 'Akses ke minimarket',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_lokasi' => 'Warung Makan',
                'bobot' => 5,
                'keterangan' => 'Akses ke warung makan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert data
        AksesLokasiPendukung::insert($locations);
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $this->command->info('Akses Lokasi Pendukung seeded successfully!');
    }
}
