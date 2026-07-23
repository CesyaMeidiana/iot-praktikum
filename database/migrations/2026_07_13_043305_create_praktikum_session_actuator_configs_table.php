<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void {
    Schema::create('praktikum_session_actuator_configs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('praktikum_session_id')->constrained()->cascadeOnDelete();
        $table->foreignId('device_actuator_id')->constrained()->cascadeOnDelete();
        $table->enum('kondisi_on_operator', ['>', '>=', '<', '<=', '='])->nullable();
        $table->double('kondisi_on_value')->nullable();
        $table->enum('kondisi_off_operator', ['>', '>=', '<', '<=', '='])->nullable();
        $table->double('kondisi_off_value')->nullable();
        $table->timestamps();
        $table->unique(['praktikum_session_id', 'device_actuator_id']);
    });
}
public function down(): void { Schema::dropIfExists('praktikum_session_actuator_configs'); }
};
