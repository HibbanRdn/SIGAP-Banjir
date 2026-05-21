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
        Schema::create('flood_risk_points', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('district')->nullable()->index();
            $table->string('subdistrict')->nullable()->index();
            $table->string('risk_level')->default('sedang')->index();
            $table->text('description')->nullable();
            $table->string('source_type')->default('dummy')->index();
            $table->text('source_reference')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->string('data_status')->default('simulasi')->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE flood_risk_points ADD COLUMN geom geometry(Point, 4326) NOT NULL');
        DB::statement('CREATE INDEX flood_risk_points_geom_gist ON flood_risk_points USING GIST (geom)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flood_risk_points');
    }
};
