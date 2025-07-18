<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Desabilitar verificação de foreign key temporariamente
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Apagar todas as mensagens dos tickets
        DB::table('ticket_messages')->truncate();
        
        // Apagar todos os tickets
        DB::table('tickets')->truncate();
        
        // Reabilitar verificação de foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Esta migration não pode ser revertida pois apaga dados permanentemente
        // Se necessário, os dados devem ser restaurados a partir de um backup
        throw new Exception('Esta migration não pode ser revertida pois apaga dados permanentemente.');
    }
};
