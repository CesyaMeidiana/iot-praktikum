<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('praktikum_sessions', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('praktikum_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->enum('topology',[
                'Point to Point',
                'Star',
                'Tree',
                'Mesh'
            ]);

            $table->enum('scenario',[
                'LOS',
                'NLOS'
            ]);

            $table->integer('distance');

            $table->enum('status',[
                'running',
                'finished'
            ])->default('running');

            $table->timestamp('started_at')->nullable();

            $table->timestamp('finished_at')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('praktikum_sessions');
    }
};