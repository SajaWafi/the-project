<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained('children')->cascadeOnDelete();
            $table->foreignId('parent_id')->constrained('parent_profiles')->cascadeOnDelete();
            $table->foreignId('panic_event_id')->nullable()->constrained('panic_events')->nullOnDelete();
            $table->string('title');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamp('sent_at')->nullable();
            $table->string('alert_type')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }
};
