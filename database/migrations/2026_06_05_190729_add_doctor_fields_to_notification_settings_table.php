<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notification_settings', function (Blueprint $table) {
            $table->boolean('doc_notif_sound')->default(true);
            $table->boolean('doc_notif_vibrate')->default(true);
            $table->boolean('doc_msg_sound')->default(true);
            $table->boolean('doc_msg_vibrate')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('notification_settings', function (Blueprint $table) {
            $table->dropColumn([
                'doc_notif_sound', 
                'doc_notif_vibrate', 
                'doc_msg_sound', 
                'doc_msg_vibrate'
            ]);
        });
    }
};
