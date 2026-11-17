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
    Schema::table('fertilizer_applications', function (Blueprint $table) {
        $table->timestamp('advance_reminder_sent_at')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('fertilizer_applications', function (Blueprint $table) {
        $table->dropColumn('advance_reminder_sent_at');
    });
}
};
