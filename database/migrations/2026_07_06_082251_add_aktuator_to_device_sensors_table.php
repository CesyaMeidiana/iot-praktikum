<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('device_sensors', function (Blueprint $table) {

            $table->string('aktuator')
                  ->nullable()
                  ->after('satuan');

        });
    }

    public function down(): void
    {
        Schema::table('device_sensors', function (Blueprint $table) {

            $table->dropColumn('aktuator');

        });
    }
};