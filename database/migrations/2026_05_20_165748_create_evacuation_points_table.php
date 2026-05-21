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
        Schema::create('evacuation_points', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->index();
            $table->text('address')->nullable();
            $table->string('district')->nullable()->index();
            $table->string('subdistrict')->nullable()->index();
            $table->unsignedInteger('capacity')->nullable();
            $table->text('facilities')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('status')->default('aktif')->index();
            $table->text('description')->nullable();
            $table->string('source_type')->default('observasi')->index();
            $table->text('source_reference')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->string('data_status')->default('simulasi')->index();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE evacuation_points ADD COLUMN geom geometry(Point, 4326) NOT NULL');
        DB::statement('CREATE INDEX evacuation_points_geom_gist ON evacuation_points USING GIST (geom)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evacuation_points');
    }
};
