<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('device_actuators', function (Blueprint $table) {

            $table->id();

            $table->foreignId('device_sensor_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('nama_aktuator');

            $table->string('kondisi_on')->nullable();

            $table->string('kondisi_off')->nullable();

            $table->text('keterangan')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('device_actuators');
    }
};