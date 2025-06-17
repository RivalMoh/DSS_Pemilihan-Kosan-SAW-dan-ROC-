<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facets\Schema;

class FreshDatabase extends Migration
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

        // Get all tables
        $tables = DB::select('SHOW TABLES');
        $databaseName = DB::connection()->getDatabaseName();
        $key = 'Tables_in_' . str_replace(['-', '.'], '_', $databaseName);
        
        // Drop all tables
        foreach ($tables as $table) {
            $tableName = $table->$key;
            if ($tableName !== 'migrations') { // Don't drop migrations table
                DB::statement("DROP TABLE IF EXISTS `$tableName`");
            }
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        // Now run the migrations for the tables we want to keep
        $this->callMigrations();
    }

    /**
     * Run the migrations for the tables we want to keep
     */
    protected function callMigrations()
    {
        // This will run all migrations in the migrations folder
        // but since we've already run them before, we'll need to clear the migrations table first
        DB::table('migrations')->truncate();
        
        // Run fresh migrations
        Artisan::call('migrate:fresh', [
            '--seed' => true,
            '--force' => true,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // This is a one-way migration
    }
}
