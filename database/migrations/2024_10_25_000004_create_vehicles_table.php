<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('plate_number', 8)->unique();
            $table->integer('spot_number')->unique()->unsigned();
            $table->string('model', 50);
            $table->string('color', 50);
            $table->timestamp('entry_at');
            $table->timestamp('exit_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('entry_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};