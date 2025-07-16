<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Category;
use Carbon\Carbon;

class DashboardReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dashboard:report {--period=today} {--user=} {--format=table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gera relatório do dashboard para análise';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $period = $this->option('period');
        $userId = $this->option('user');
        $format = $this->option('format');
        
        $this->info("Gerando relatório do dashboard - Período: {$period}");
        
        // Definir período
        $dateRange = $this->getDateRange($period);
        
        // Filtrar usuário se especificado
        $user = null;
        if ($userId) {
            $user = User::find($userId);
            if (!$user) {
                $this->error("Usuário não encontrado!");
                return;
            }
            $this->info("Usuário: {$user->name}");
        }
        
        // Gerar estatísticas
        $stats = $this->getStats($dateRange, $user);
        
        // Exibir relatório
        if ($format === 'table') {
            $this->displayTable($stats);
        } else {
            $this->displayJson($stats);
        }
        
        $this->newLine();
        $this->info("Relatório gerado com sucesso!");
    }
    
    private function getDateRange($period)
    {
        $now = Carbon::now();
        
        switch ($period) {
            case 'today':
                return [
                    'start' => $now->copy()->startOfDay(),
                    'end' => $now->copy()->endOfDay()
                ];
            case 'week':
                return [
                    'start' => $now->copy()->startOfWeek(),
                    'end' => $now->copy()->endOfWeek()
                ];
            case 'month':
                return [
                    'start' => $now->copy()->startOfMonth(),
                    'end' => $now->copy()->endOfMonth()
                ];
            default:
                return [
                    'start' => $now->copy()->startOfDay(),
                    'end' => $now->copy()->endOfDay()
                ];
        }
    }
    
    private function getStats($dateRange, $user = null)
    {
        $baseQuery = Ticket::query();
        
        if ($user) {
            if ($user->tipo === 'usuario') {
                $baseQuery->where('usuario_id', $user->id);
            }
            // Para atendentes, mostrar dados gerais do sistema
            // Não aplicar filtro - eles veem todos os tickets
        }
        
        $stats = [
            'total_tickets' => (clone $baseQuery)->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])->count(),
            'tickets_resolvidos' => (clone $baseQuery)->whereBetween('resolvido_em', [$dateRange['start'], $dateRange['end']])->whereNotNull('resolvido_em')->count(),
            'tickets_em_atendimento' => (clone $baseQuery)->whereNull('resolvido_em')->whereNotNull('assumed_at')->whereNull('paused_at')->count(),
            'tickets_pausados' => (clone $baseQuery)->whereNull('resolvido_em')->whereNotNull('paused_at')->count(),
            'tickets_aguardando' => Ticket::whereNull('assumed_at')->whereNull('resolvido_em')->count(),
        ];
        
        // Tempo médio de resolução
        $resolvedTickets = (clone $baseQuery)
            ->whereBetween('resolvido_em', [$dateRange['start'], $dateRange['end']])
            ->whereNotNull('resolvido_em');
        
        $totalTimeSpent = $resolvedTickets->sum('total_time_spent');
        $resolvedCount = $resolvedTickets->count();
        
        $stats['tempo_medio_resolucao'] = $resolvedCount > 0 ? $totalTimeSpent / $resolvedCount : 0;
        
        // Distribuição por prioridade
        $prioridades = Ticket::whereNull('resolvido_em')
            ->groupBy('prioridade')
            ->selectRaw('prioridade, COUNT(*) as count')
            ->pluck('count', 'prioridade')
            ->toArray();
        
        $stats['prioridades'] = $prioridades;
        
        // Distribuição por categoria
        $categorias = Ticket::whereNull('resolvido_em')
            ->join('categories', 'tickets.categoria_id', '=', 'categories.id')
            ->groupBy('categories.nome')
            ->selectRaw('categories.nome, COUNT(*) as count')
            ->pluck('count', 'nome')
            ->toArray();
        
        $stats['categorias'] = $categorias;
        
        return $stats;
    }
    
    private function displayTable($stats)
    {
        $this->newLine();
        $this->info("=== ESTATÍSTICAS GERAIS ===");
        
        $this->table(
            ['Métrica', 'Valor'],
            [
                ['Total de Tickets', $stats['total_tickets']],
                ['Tickets Resolvidos', $stats['tickets_resolvidos']],
                ['Em Atendimento', $stats['tickets_em_atendimento']],
                ['Pausados', $stats['tickets_pausados']],
                ['Aguardando', $stats['tickets_aguardando']],
                ['Tempo Médio de Resolução', gmdate('H:i:s', $stats['tempo_medio_resolucao'])],
            ]
        );
        
        if (!empty($stats['prioridades'])) {
            $this->newLine();
            $this->info("=== DISTRIBUIÇÃO POR PRIORIDADE ===");
            
            $rows = [];
            foreach ($stats['prioridades'] as $prioridade => $count) {
                $rows[] = [$prioridade, $count];
            }
            
            $this->table(['Prioridade', 'Quantidade'], $rows);
        }
        
        if (!empty($stats['categorias'])) {
            $this->newLine();
            $this->info("=== DISTRIBUIÇÃO POR CATEGORIA ===");
            
            $rows = [];
            foreach ($stats['categorias'] as $categoria => $count) {
                $rows[] = [$categoria, $count];
            }
            
            $this->table(['Categoria', 'Quantidade'], $rows);
        }
    }
    
    private function displayJson($stats)
    {
        $this->line(json_encode($stats, JSON_PRETTY_PRINT));
    }
}
