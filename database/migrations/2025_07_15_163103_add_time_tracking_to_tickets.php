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
        Schema::table('tickets', function (Blueprint $table) {
            $table->timestamp('assumed_at')->nullable();
            $table->timestamp('paused_at')->nullable();
            $table->integer('total_time_spent')->default(0); // Tempo total em segundos
            $table->integer('paused_time')->default(0); // Tempo em pausa em segundos
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(['assumed_at', 'paused_at', 'total_time_spent', 'paused_time']);
        });
    }
};
