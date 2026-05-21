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
        Schema::create('heavy_equipment_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('heavy_equipment_posts')->cascadeOnDelete();
            $table->foreignId('equipment_type_id')->constrained('equipment_types')->restrictOnDelete();
            $table->unsignedInteger('quantity')->default(0);
            $table->unsignedInteger('available_quantity')->default(0);
            $table->string('status')->default('tersedia')->index();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('post_id');
            $table->index('equipment_type_id');
        });

        DB::statement('ALTER TABLE heavy_equipment_units ADD CONSTRAINT heavy_equipment_units_available_lte_quantity CHECK (available_quantity <= quantity)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('heavy_equipment_units');
    }
};
