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
    Schema::table('device_actuators', function (Blueprint $table) {
        $table->enum('kondisi_on_operator', ['>', '>=', '<', '<=', '='])->nullable()->after('kondisi_on');
        $table->double('kondisi_on_value')->nullable()->after('kondisi_on_operator');
        $table->enum('kondisi_off_operator', ['>', '>=', '<', '<=', '='])->nullable()->after('kondisi_off');
        $table->double('kondisi_off_value')->nullable()->after('kondisi_off_operator');
        $table->dropColumn(['kondisi_on', 'kondisi_off']);
    });
}

public function down(): void
{
    Schema::table('device_actuators', function (Blueprint $table) {
        $table->string('kondisi_on')->nullable();
        $table->string('kondisi_off')->nullable();
        $table->dropColumn(['kondisi_on_operator', 'kondisi_on_value', 'kondisi_off_operator', 'kondisi_off_value']);
    });
}
};
