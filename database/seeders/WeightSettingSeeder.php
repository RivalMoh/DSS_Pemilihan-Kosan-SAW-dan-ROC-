<?php

namespace Database\Seeders;

use App\Models\WeightSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WeightSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Disable foreign key checks to avoid constraint issues
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Truncate the table to start fresh
        DB::table('weight_settings')->truncate();
        
        // Initialize default weight settings
        WeightSetting::initializeDefaults();
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $this->command->info('Weight settings seeded successfully!');
    }
}
