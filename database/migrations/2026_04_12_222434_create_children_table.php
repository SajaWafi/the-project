<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('children', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->constrained('parent_profiles')->cascadeOnDelete();
            $table->string('name');
            $table->string('gender')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('autism_level')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('children');
    }
};
