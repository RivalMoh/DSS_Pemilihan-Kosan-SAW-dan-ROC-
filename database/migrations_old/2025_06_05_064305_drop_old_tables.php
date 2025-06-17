<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Drop old tables if they exist
        $tablesToDrop = [
            'alternatifs',
            'end_values',
            'fasilitas',
            'komentars',
            'kos',
            'kriteria',
            'luas_kamars',
            'nilai_utilities',
            'tipis',
            'user_preferences',
            'standard_features',
            'standard_feature_options',
            'special_features',
            'special_feature_options',
            'kosan_special_feature_options',
            'dharga',
            'djarak'
        ];

        foreach ($tablesToDrop as $table) {
            if (Schema::hasTable($table)) {
                Schema::dropIfExists($table);
            }
        }
    }

    public function down()
    {
        // This is a destructive migration, so we won't implement the down method
        // as it would be complex to recreate all the old tables
    }
};
