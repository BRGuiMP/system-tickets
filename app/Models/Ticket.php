<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    
    protected $casts = [
        'assumed_at' => 'datetime',
        'paused_at' => 'datetime',
        'resolvido_em' => 'datetime',
        'data_agendamento' => 'datetime'
    ];

    protected $fillable = [
        'titulo',
        'descricao',
        'categoria_id',
        'status',
        'prioridade',
        'usuario_id',
        'atendente_id',
        'resolvido_em',
        'assumed_at',
        'paused_at',
        'total_time_spent',
        'paused_time',
        'data_agendamento'
    ];

    public function categoria()
    {
        return $this->belongsTo(Category::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function atendente()
    {
        return $this->belongsTo(User::class, 'atendente_id');
    }

    /**
     * Assume o ticket para atendimento
     */
    public function assume()
    {
        if (!$this->assumed_at) {
            $this->assumed_at = now();
            $this->atendente_id = auth()->id();
            $this->status = 'Em Andamento';
            $this->save();
        }
    }

    /**
     * Pausa o atendimento do ticket
     */
    public function pause()
    {
        if ($this->assumed_at && !$this->paused_at) {
            $this->paused_at = now();
            $this->total_time_spent = $this->calculateTimeSpent();
            $this->save();
        }
    }

    /**
     * Retoma o atendimento do ticket
     */
    public function resume()
    {
        if ($this->paused_at) {
            $pausedDuration = now()->diffInSeconds($this->paused_at);
            $this->paused_time = ($this->paused_time ?? 0) + $pausedDuration;
            $this->paused_at = null;
            $this->save();
        }
    }

    /**
     * Finaliza o atendimento do ticket
     */
    public function resolve()
    {
        if ($this->assumed_at) {
            $this->total_time_spent = $this->calculateTimeSpent();
            $this->resolvido_em = now();
            $this->status = 'Resolvido';
            $this->save();
        }
    }

    /**
     * Calcula o tempo total gasto no atendimento
     */
    private function calculateTimeSpent()
    {
        $end = $this->paused_at ?? now();
        
        // Para tickets agendados, usar a data de agendamento como início
        // Para tickets normais, usar assumed_at
        $start = $this->getStartTime();
        
        $totalSeconds = $start->diffInSeconds($end);
        return $totalSeconds - ($this->paused_time ?? 0);
    }

    /**
     * Retorna a data/hora de início efetiva para cálculo de tempo
     */
    private function getStartTime()
    {
        return $this->data_agendamento ?? $this->assumed_at;
    }

    /**
     * Verifica se o ticket está pausado
     */
    public function isPaused()
    {
        return !is_null($this->paused_at);
    }

    /**
     * Verifica se o ticket está em atendimento
     */
    public function isInProgress()
    {
        return !is_null($this->assumed_at) && is_null($this->resolvido_em);
    }

    /**
     * Retorna o tempo total de atendimento formatado
     */
    public function getFormattedTotalTime()
    {
        if (!$this->assumed_at) {
            return 'Não iniciado';
        }

        $seconds = $this->total_time_spent ?? $this->calculateTimeSpent();
        
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $remainingSeconds = $seconds % 60;

        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $remainingSeconds);
        }
        
        return sprintf('%02d:%02d', $minutes, $remainingSeconds);
    }

    public function mensagens()
    {
        return $this->hasMany(TicketMessage::class);
    }
}
