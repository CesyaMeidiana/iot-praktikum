<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $table) {

            $table->id();

            $table->foreignId('lecturer_id')->constrained('users')->cascadeOnDelete();

            $table->foreignId('classroom_id')->constrained()->cascadeOnDelete();

            $table->string('title');

            $table->longText('description')->nullable();

            $table->enum('target',[
                'individual',
                'group'
            ]);

            $table->dateTime('deadline');

            $table->string('attachment')->nullable();

            $table->json('topologies')->nullable();

            $table->json('scenarios')->nullable();

            $table->json('distances')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};