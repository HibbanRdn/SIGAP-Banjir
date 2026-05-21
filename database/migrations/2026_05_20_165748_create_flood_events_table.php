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
        Schema::create('flood_events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('district')->nullable()->index();
            $table->string('subdistrict')->nullable()->index();
            $table->string('severity_level')->default('sedang')->index();
            $table->unsignedInteger('water_depth_cm')->nullable();
            $table->string('status')->default('aktif')->index();
            $table->text('description')->nullable();
            $table->string('source_type')->default('admin_input')->index();
            $table->text('source_reference')->nullable();
            $table->dateTime('occurred_at')->nullable();
            $table->dateTime('reported_at');
            $table->boolean('is_verified')->default(false);
            $table->string('data_status')->default('simulasi')->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE flood_events ADD COLUMN geom geometry(Point, 4326) NOT NULL');
        DB::statement('CREATE INDEX flood_events_geom_gist ON flood_events USING GIST (geom)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flood_events');
    }
};
