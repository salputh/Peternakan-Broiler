<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('email')->unique();
            $table->string('phone', 20);
            $table->string('password');
            $table->enum('role', ['developer', 'owner', 'manager', 'operator'])->default('owner');
            $table->string('photo')->nullable();

            // FK ke peternakan nanti ditambahkan di migration peternakan
            $table->foreignId('peternakan_id')
                ->nullable()
                ->constrained('peternakans')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
