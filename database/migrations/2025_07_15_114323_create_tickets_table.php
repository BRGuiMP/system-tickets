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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descricao');
            $table->foreignId('categoria_id')->constrained('categories');
            $table->enum('status', ['Aberto', 'Em Andamento', 'Resolvido', 'Fechado', 'Cancelado'])->default('Aberto');
            $table->enum('prioridade', ['Baixa', 'Média', 'Alta', 'Urgente'])->default('Média');
            $table->foreignId('usuario_id')->constrained('users');
            $table->foreignId('atendente_id')->nullable()->constrained('users');
            $table->timestamp('resolvido_em')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
