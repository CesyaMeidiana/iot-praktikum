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
        $table->foreignId('classroom_id')
            ->nullable()
            ->after('praktikum_id')
            ->constrained()
            ->nullOnDelete();
    });
}

public function down(): void
{
    Schema::table('praktikum_sessions', function (Blueprint $table) {
        $table->dropForeign(['classroom_id']);
        $table->dropColumn('classroom_id');
    });
}
};
