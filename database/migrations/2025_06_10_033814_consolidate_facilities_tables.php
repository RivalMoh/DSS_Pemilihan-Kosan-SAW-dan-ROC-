<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create new consolidated facilities table
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['kamar', 'kamar_mandi', 'umum']);
            $table->integer('weight')->default(1);
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        // Create single junction table
        Schema::create('kosan_facility', function (Blueprint $table) {
            $table->id();
            $table->string('kosan_id');
            $table->foreignId('facility_id')->constrained('facilities');
            $table->timestamps();

            $table->foreign('kosan_id')->references('UniqueID')->on('kosan')->onDelete('cascade');
            $table->unique(['kosan_id', 'facility_id']);
        });

        // Migrate existing data
        $this->migrateExistingData();
    }

    /**
     * Migrate data from old tables to new structure
     */
    protected function migrateExistingData(): void
    {
        // Migrate kamar facilities
        $kamarFacilities = DB::table('fasilitas_kamar')->get();
        foreach ($kamarFacilities as $facility) {
            $id = DB::table('facilities')->insertGetId([
                'name' => $facility->nama_fasilitas,
                'type' => 'kamar',
                'weight' => $facility->bobot,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Migrate relationships
            $relations = DB::table('kosan_fasilitas_kamar')
                ->where('fasilitas_kamar_id', $facility->id)
                ->get();

            foreach ($relations as $relation) {
                DB::table('kosan_facility')->insert([
                    'kosan_id' => $relation->kosan_id,
                    'facility_id' => $id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Migrate kamar_mandi facilities
        $kamarMandiFacilities = DB::table('fasilitas_kamar_mandi')->get();
        foreach ($kamarMandiFacilities as $facility) {
            $id = DB::table('facilities')->insertGetId([
                'name' => $facility->nama_fasilitas,
                'type' => 'kamar_mandi',
                'weight' => $facility->bobot,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Migrate relationships
            $relations = DB::table('kosan_fasilitas_kamar_mandi')
                ->where('fasilitas_kamar_mandi_id', $facility->id)
                ->get();

            foreach ($relations as $relation) {
                DB::table('kosan_facility')->insert([
                    'kosan_id' => $relation->kosan_id,
                    'facility_id' => $id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Migrate umum facilities
        $umumFacilities = DB::table('fasilitas_umum')->get();
        foreach ($umumFacilities as $facility) {
            $id = DB::table('facilities')->insertGetId([
                'name' => $facility->nama_fasilitas,
                'type' => 'umum',
                'weight' => $facility->bobot,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Migrate relationships
            $relations = DB::table('kosan_fasilitas_umum')
                ->where('fasilitas_umum_id', $facility->id)
                ->get();

            foreach ($relations as $relation) {
                DB::table('kosan_facility')->insert([
                    'kosan_id' => $relation->kosan_id,
                    'facility_id' => $id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the new tables
        Schema::dropIfExists('kosan_facility');
        Schema::dropIfExists('facilities');

        // Note: Old tables are not recreated on rollback as we can't guarantee data consistency
    }
};
