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
        Schema::create('praktikum_session_devices', function (Blueprint $table) {

    $table->id();

    $table->foreignId('praktikum_session_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('device_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->timestamps();

});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('praktikum_session_devices');
    }
};
