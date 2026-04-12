<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('workplaces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('doctor_profiles')->cascadeOnDelete();
            $table->string('place_name');
            $table->unsignedTinyInteger('from_hour');
            $table->unsignedTinyInteger('from_minute')->default(0);
            $table->string('from_period', 2); // AM / PM
            $table->unsignedTinyInteger('to_hour');
            $table->unsignedTinyInteger('to_minute')->default(0);
            $table->string('to_period', 2); // AM / PM
            $table->json('days')->nullable(); // ["sun","mon"]
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workplaces');
    }
};