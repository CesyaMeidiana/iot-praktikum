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
    Schema::table('users', function (Blueprint $table) {

        $table->string('nim_nip')->nullable()->unique();

        $table->string('phone')->nullable();

        $table->string('photo')->nullable();

        $table->boolean('status')->default(true);

        $table->timestamp('last_login')->nullable();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('users', function (Blueprint $table) {

        $table->dropColumn([
            'nim_nip',
            'photo',
            'status',
            'last_login'
        ]);

    });
}
};
