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
    Schema::table('device_actuators', function (Blueprint $table) {
        $table->dropColumn(['kondisi_on_operator', 'kondisi_on_value', 'kondisi_off_operator', 'kondisi_off_value']);
    });
}
public function down(): void {
    Schema::table('device_actuators', function (Blueprint $table) {
        $table->string('kondisi_on_operator')->nullable();
        $table->double('kondisi_on_value')->nullable();
        $table->string('kondisi_off_operator')->nullable();
        $table->double('kondisi_off_value')->nullable();
    });
}
};
