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
       Schema::create('comparison_rules', function (Blueprint $table) {
    $table->id();

    $table->string('rule_key')->unique();

    $table->decimal('min_value', 5, 2)->nullable();
    $table->decimal('max_value', 5, 2)->nullable();

    $table->string('status'); // stable, increase, improving

    $table->text('message_ar');
    $table->string('message_en')->nullable();

    $table->boolean('is_active')->default(true);

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recommendation_rules_table');
    }
};
