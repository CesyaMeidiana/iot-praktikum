<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('monitoring_logs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('praktikum_session_id')->nullable()->constrained()->nullOnDelete();
        $table->foreignId('device_id')->constrained()->cascadeOnDelete();
        $table->string('packet_id')->nullable();
        $table->string('topologi')->nullable();
        $table->double('jarak')->nullable();
        $table->double('throughput')->nullable();
        $table->double('delay')->nullable();
        $table->double('jitter')->nullable();
        $table->double('packet_loss')->nullable();
        $table->string('kondisi')->nullable();
        $table->json('readings'); // {"Suhu": 28.5, "Mini Fan": "ON", ...}
        $table->timestamps();
    });
}

public function down(): void { Schema::dropIfExists('monitoring_logs'); }
};
