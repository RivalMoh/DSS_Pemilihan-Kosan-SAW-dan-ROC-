<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DssTablesSeeder extends Seeder
{
    public function run(): void
    {
        // Luas Kamar
        DB::table('luas_kamar')->insert([
            [
                'range_luas' => '< 9 m²', 
                'min_value' => 0, 
                'max_value' => 9, 
                'nilai' => 1, 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'range_luas' => '9 - 11.9 m²', 
                'min_value' => 9, 
                'max_value' => 11.9, 
                'nilai' => 2, 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'range_luas' => '12 - 14.9 m²', 
                'min_value' => 12, 
                'max_value' => 14.9, 
                'nilai' => 3, 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'range_luas' => '15 - 17,9 m²', 
                'min_value' => 15, 
                'max_value' => 17.9, 
                'nilai' => 4, 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'range_luas' => '> 18 m²', 
                'min_value' => 18, 
                'max_value' => 1000, // A high number to represent infinity
                'nilai' => 5, 
                'created_at' => now(), 
                'updated_at' => now()
            ],
        ]);

        // Harga Sewa (in Rupiah)
        DB::table('harga_sewa')->insert([
            [
                'range_harga' => '< 6.000.000', 
                'min_value' => 0, 
                'max_value' => 6000000, 
                'nilai' => 5, 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'range_harga' => '6.000.001 - 9.000.000', 
                'min_value' => 6000001, 
                'max_value' => 9000000, 
                'nilai' => 4, 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'range_harga' => '9.000.001 - 12.000.000', 
                'min_value' => 9000001, 
                'max_value' => 12000000, 
                'nilai' => 3, 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'range_harga' => '12.000.001 - 15.000.000', 
                'min_value' => 12000001, 
                'max_value' => 15000000, 
                'nilai' => 2, 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'range_harga' => '> 15.000.000', 
                'min_value' => 15000001, 
                'max_value' => 100000000, // A high number to represent infinity
                'nilai' => 1, 
                'created_at' => now(), 
                'updated_at' => now()
            ],
        ]);

        // Estimasi Jarak (in kilometers)
        DB::table('estimasi_jarak')->insert([
            [
                'range_jarak' => '< 0.5 km', 
                'min_value' => 0, 
                'max_value' => 0.5, 
                'nilai' => 5, 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'range_jarak' => '0.5 - 1 km', 
                'min_value' => 0.5, 
                'max_value' => 1, 
                'nilai' => 4, 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'range_jarak' => '1.1 - 2 km', 
                'min_value' => 1.1, 
                'max_value' => 2, 
                'nilai' => 3, 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'range_jarak' => '2.1 - 3 km', 
                'min_value' => 2.1, 
                'max_value' => 3, 
                'nilai' => 2, 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'range_jarak' => '> 3 km', 
                'min_value' => 3.1, 
                'max_value' => 1000, // A high number to represent infinity
                'nilai' => 1, 
                'created_at' => now(), 
                'updated_at' => now()
            ],
        ]);
    }
}
