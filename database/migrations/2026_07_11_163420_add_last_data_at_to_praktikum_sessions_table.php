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
    Schema::table('praktikum_sessions', function (Blueprint $table) {
        $table->timestamp('last_data_at')->nullable()->after('started_at');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('praktikum_sessions', function (Blueprint $table) {
        $table->dropColumn('last_data_at');
    });
}
};
