<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('conversations', function (Blueprint $table) {
        $table->timestamp('doctor_muted_until')->nullable()->after('child_id');
        $table->timestamp('parent_muted_until')->nullable()->after('doctor_muted_until');
    });
}

public function down()
{
    Schema::table('conversations', function (Blueprint $table) {
        $table->dropColumn(['doctor_muted_until', 'parent_muted_until']);
    });
}
};
