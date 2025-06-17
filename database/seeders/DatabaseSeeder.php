<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\FacilitiesSeeder;
use Database\Seeders\KosanFacilityPivotSeeder;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Truncate all tables in the correct order
        $tables = [
            'weight_settings',  // Add weight_settings to the truncation list
            'users',
            'keamanan',
            'kebersihan',
            'iuran',
            'aturan',
            'ventilasi',
            'fasilitas_kamar',
            'fasilitas_kamar_mandi',
            'fasilitas_umum',
            'akses_lokasi_pendukung',
            'kosan_akses_lokasi_pendukung',
            'luas_kamar',
            'harga_sewa',
            'estimasi_jarak',
            'kosan'
        ];

        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Run seeders in order
        $this->call([
            // Seed users first
            UsersTableSeeder::class,
            // Seed supporting tables
            SupportingTablesSeeder::class,
            // Seed facilities
            FacilitiesSeeder::class,
            // Seed akses lokasi pendukung
            AksesLokasiPendukungSeeder::class,
            // Seed weight settings
            WeightSettingSeeder::class,
            // Seed kosan data
            KosanSeeder::class,
            // Seed kosan facility relationships
            KosanFacilityPivotSeeder::class,
            KosanAksesLokasiPendukungSeeder::class,
            // Seed DSS tables
            DssTablesSeeder::class,
        ]);
    }
}
