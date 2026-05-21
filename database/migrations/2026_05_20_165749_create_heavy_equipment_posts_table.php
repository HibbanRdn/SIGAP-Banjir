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
        Schema::create('heavy_equipment_posts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('district')->nullable()->index();
            $table->string('subdistrict')->nullable()->index();
            $table->string('contact_person')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('status')->default('aktif')->index();
            $table->text('description')->nullable();
            $table->string('source_type')->default('dummy')->index();
            $table->text('source_reference')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->string('data_status')->default('dummy')->index();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE heavy_equipment_posts ADD COLUMN geom geometry(Point, 4326) NOT NULL');
        DB::statement('CREATE INDEX heavy_equipment_posts_geom_gist ON heavy_equipment_posts USING GIST (geom)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('heavy_equipment_posts');
    }
};
