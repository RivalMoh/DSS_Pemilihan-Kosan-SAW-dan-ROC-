<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CleanupOldTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // List of tables to keep
        $tablesToKeep = [
            'users',
            'kosan',
            'keamanan',
            'kebersihan',
            'iuran',
            'aturan',
            'ventilasi',
            'fasilitas_kamar',
            'fasilitas_kamar_mandi',
            'fasilitas_umum',
            'kosan_fasilitas_kamar',
            'kosan_fasilitas_kamar_mandi',
            'kosan_fasilitas_umum',
            'estimasi_jarak',
            'harga_sewa',
            'luas_kamar',
            'migrations',
            'password_reset_tokens',
            'sessions',
            'failed_jobs',
            'personal_access_tokens',
        ];

        // Get all tables in the database
        $tables = DB::select('SHOW TABLES');
        
        // Get the database name from config
        $databaseName = DB::connection()->getDatabaseName();
        $key = 'Tables_in_' . str_replace(['-', '.'], '_', $databaseName);
        
        // Drop tables that are not in the keep list
        foreach ($tables as $table) {
            $tableName = $table->$key;
            if (!in_array($tableName, $tablesToKeep)) {
                Schema::dropIfExists($tableName);
            }
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // This migration doesn't support rollback
    }
}
