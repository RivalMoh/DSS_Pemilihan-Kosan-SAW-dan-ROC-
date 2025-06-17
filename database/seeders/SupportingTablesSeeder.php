<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupportingTablesSeeder extends Seeder
{
    public function run(): void
    {
        // Keamanan
        DB::table('keamanan')->insert([
            ['tingkat_keamanan' => 'Pagar, Kamera CCTV, & Penjaga Kos', 'nilai' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['tingkat_keamanan' => 'Penjaga Kos', 'nilai' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['tingkat_keamanan' => 'Kamera CCTV', 'nilai' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['tingkat_keamanan' => 'Pagar', 'nilai' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['tingkat_keamanan' => 'Tidak Ada Keamanan', 'nilai' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Kebersihan
        DB::table('kebersihan')->insert([
            ['tingkat_kebersihan' => 'Kebersihan Dapur Bersama', 'nilai' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['tingkat_kebersihan' => 'Kebersihan Kamar Mandi Umum', 'nilai' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['tingkat_kebersihan' => 'Kebersihan Ruang Tamu', 'nilai' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['tingkat_kebersihan' => 'Pengelolaan Tempat Sampah yang Baik', 'nilai' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['tingkat_kebersihan' => 'Bebas dari Hama (Kecoa, Tikus, Nyamuk, dll)', 'nilai' => 5, 'created_at' => now(), 'updated_at' => now()],

        ]);

        // Iuran
        DB::table('iuran')->insert([
            ['kategori' => '>Rp200.000', 'nilai' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kategori' => 'Rp150.000 - Rp200.000', 'nilai' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['kategori' => 'Rp100.000 - Rp150.000', 'nilai' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['kategori' => 'Rp50.000 - Rp100.000', 'nilai' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['kategori' => '<Rp50.000', 'nilai' => 5, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Aturan
        DB::table('aturan')->insert([
            ['jenis_aturan' => 'Jam malam ketat dibawah jam 9 malam, tidak boleh ada tamu, tidak boleh berisik', 'nilai' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_aturan' => 'Jam malam sedang dibawah jam 10 malam, tamu terbatas waktu, boleh masak dengan jadwal', 'nilai' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_aturan' => 'Jam malam normal dibawah jam 11 malam, boleh ada tamu, bebas masak', 'nilai' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_aturan' => 'Jam malam longgar dibawah jam 12 malam, tamu dapat menginap dengan izin, bebas aktivitas', 'nilai' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_aturan' => 'Jam malam fleksibel, boleh ada tamu, aturan minimal', 'nilai' => 5, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Ventilasi
        DB::table('ventilasi')->insert([
            ['kondisi_ventilasi' => 'Sangat Baik (Cross Ventilation, udara segar)', 'nilai' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['kondisi_ventilasi' => 'Baik (Jendela Besar, Sirkulasi Lancar)', 'nilai' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['kondisi_ventilasi' => 'Cukup (Jendela Kecil, Sirkulasi Terbatas)', 'nilai' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['kondisi_ventilasi' => 'Buruk (Ventilasi Minimal)', 'nilai' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['kondisi_ventilasi' => 'Tidak Ada Ventilasi', 'nilai' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
