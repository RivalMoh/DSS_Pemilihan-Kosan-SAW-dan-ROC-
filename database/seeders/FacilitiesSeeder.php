<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacilitiesSeeder extends Seeder
{
    public function run(): void
    {
        // Fasilitas Kamar
        DB::table('fasilitas_kamar')->insert([
            ['nama_fasilitas' => 'Kasur Busa', 'bobot' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['nama_fasilitas' => 'Kasur Spring Bed', 'bobot' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['nama_fasilitas' => 'Lemari Kayu', 'bobot' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['nama_fasilitas' => 'Lemari Plastik', 'bobot' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['nama_fasilitas' => 'Meja Belajar', 'bobot' => 9, 'created_at' => now(), 'updated_at' => now()],
            // ['nama_fasilitas' => 'Kursi', 'bobot' => 8, 'created_at' => now(), 'updated_at' => now()],
            ['nama_fasilitas' => 'AC', 'bobot' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['nama_fasilitas' => 'Kipas Angin', 'bobot' => 9, 'created_at' => now(), 'updated_at' => now()],
            ['nama_fasilitas' => 'Kamar Mandi Dalam', 'bobot' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['nama_fasilitas' => 'Kamar Mandi Luar', 'bobot' => 8, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Fasilitas Kamar Mandi
        DB::table('fasilitas_kamar_mandi')->insert([
            ['nama_fasilitas' => 'Kloset Duduk', 'bobot' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['nama_fasilitas' => 'Kloset Jongkok', 'bobot' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['nama_fasilitas' => 'Shower', 'bobot' => 9, 'created_at' => now(), 'updated_at' => now()],
            ['nama_fasilitas' => 'Gayung', 'bobot' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['nama_fasilitas' => 'Sikat WC', 'bobot' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['nama_fasilitas' => 'Wastafel', 'bobot' => 8, 'created_at' => now(), 'updated_at' => now()],
            ['nama_fasilitas' => 'Ember', 'bobot' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['nama_fasilitas' => 'Exhaust fan', 'bobot' => 9, 'created_at' => now(), 'updated_at' => now()],
            ['nama_fasilitas' => 'Water Heater', 'bobot' => 10, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Fasilitas Umum
        DB::table('fasilitas_umum')->insert([
            ['nama_fasilitas' => 'Dapur Bersama', 'bobot' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nama_fasilitas' => 'Ruang Tamu', 'bobot' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['nama_fasilitas' => 'Mesin Cuci', 'bobot' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['nama_fasilitas' => 'Tempat Parkir', 'bobot' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['nama_fasilitas' => 'Wifi', 'bobot' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['nama_fasilitas' => 'Kulkas', 'bobot' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['nama_fasilitas' => 'Tempat Jemuran', 'bobot' => 5, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
