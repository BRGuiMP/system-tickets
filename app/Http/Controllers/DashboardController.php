<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $period = $request->get('period', 'today'); // today, week, month, custom
        
        // Definir período baseado na seleção
        $dateRange = $this->getDateRange($period, $request);
        
        // Estatísticas gerais
        $stats = $this->getGeneralStats($user, $dateRange);
        
        // Estatísticas de performance
        $performance = $this->getPerformanceStats($user, $dateRange);
        
        // Estatísticas de workload
        $workload = $this->getWorkloadStats($user);
        
        // Dados para gráficos
        $chartData = $this->getChartData($user, $dateRange);
        
        // Estatísticas da equipe (apenas para atendentes)
        $teamStats = [];
        if ($user->tipo === 'atendente') {
            $teamStats = $this->getTeamStats($dateRange);
        }
        
        // Alertas e notificações
        $alerts = $this->getAlerts($user);
        
        return view('dashboard.index', compact(
            'stats',
            'performance',
            'workload',
            'chartData',
            'teamStats',
            'alerts',
            'period'
        ));
    }
    
    private function getDateRange($period, $request)
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
            case 'custom':
                return [
                    'start' => $request->get('start_date') ? Carbon::parse($request->get('start_date')) : $now->copy()->startOfDay(),
                    'end' => $request->get('end_date') ? Carbon::parse($request->get('end_date')) : $now->copy()->endOfDay()
                ];
            default:
                return [
                    'start' => $now->copy()->startOfDay(),
                    'end' => $now->copy()->endOfDay()
                ];
        }
    }
    
    private function getGeneralStats($user, $dateRange)
    {
        $baseQuery = Ticket::query();
        
        // Se for usuário comum, mostrar apenas seus tickets
        if ($user->tipo === 'usuario') {
            $baseQuery->where('usuario_id', $user->id);
        }
        // Se for atendente, mostrar todos os tickets (visão geral do sistema)
        // Não aplicar filtro para atendentes - eles veem tudo
        
        return [
            'total_tickets' => (clone $baseQuery)->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])->count(),
            'tickets_resolvidos' => (clone $baseQuery)->whereBetween('resolvido_em', [$dateRange['start'], $dateRange['end']])->whereNotNull('resolvido_em')->count(),
            'tickets_em_atendimento' => (clone $baseQuery)->whereNull('resolvido_em')->whereNotNull('assumed_at')->whereNull('paused_at')->count(),
            'tickets_pausados' => (clone $baseQuery)->whereNull('resolvido_em')->whereNotNull('paused_at')->count(),
            'tickets_aguardando' => Ticket::whereNull('assumed_at')->whereNull('resolvido_em')->count(),
        ];
    }
    
    private function getPerformanceStats($user, $dateRange)
    {
        // Para indicadores de performance, mostrar dados pessoais do atendente
        // mas para usuários comuns, mostrar apenas seus tickets
        $baseQuery = Ticket::query();
        
        if ($user->tipo === 'usuario') {
            $baseQuery->where('usuario_id', $user->id);
        } elseif ($user->tipo === 'atendente') {
            // Para atendentes, mostrar apenas tickets que ele atendeu para métricas pessoais
            $baseQuery->where('atendente_id', $user->id);
        }
        
        $resolvedTickets = (clone $baseQuery)
            ->whereBetween('resolvido_em', [$dateRange['start'], $dateRange['end']])
            ->whereNotNull('resolvido_em');
        
        $totalTimeSpent = $resolvedTickets->sum('total_time_spent');
        $resolvedCount = $resolvedTickets->count();
        
        $avgResolutionTime = $resolvedCount > 0 ? $totalTimeSpent / $resolvedCount : 0;
        
        // Taxa de resolução
        $assumedTickets = (clone $baseQuery)->whereNotNull('assumed_at')->count();
        $resolutionRate = $assumedTickets > 0 ? ($resolvedCount / $assumedTickets) * 100 : 0;
        
        return [
            'tempo_medio_resolucao' => $avgResolutionTime,
            'tempo_total_hoje' => (clone $baseQuery)->whereBetween('updated_at', [$dateRange['start'], $dateRange['end']])->sum('total_time_spent'),
            'taxa_resolucao' => $resolutionRate,
            'tickets_primeiro_contato' => $this->getFirstContactResolution($user, $dateRange),
        ];
    }
    
    private function getWorkloadStats($user)
    {
        $baseQuery = Ticket::query();
        
        if ($user->tipo === 'usuario') {
            $baseQuery->where('usuario_id', $user->id);
        } elseif ($user->tipo === 'atendente') {
            // Para "meus_tickets_pendentes", mostrar apenas os tickets do atendente
            // Para distribuições, mostrar visão geral do sistema
            $baseQuery->where('atendente_id', $user->id);
        }
        
        return [
            'meus_tickets_pendentes' => (clone $baseQuery)->whereNull('resolvido_em')->whereNotNull('assumed_at')->count(),
            'distribuicao_prioridade' => $this->getPriorityDistribution($user),
            'distribuicao_categoria' => $this->getCategoryDistribution($user),
        ];
    }
    
    private function getChartData($user, $dateRange)
    {
        // Dados para gráfico de evolução diária
        $dailyData = [];
        $currentDate = $dateRange['start']->copy();
        
        while ($currentDate <= $dateRange['end']) {
            $baseQuery = Ticket::query();
            
            if ($user->tipo === 'usuario') {
                $baseQuery->where('usuario_id', $user->id);
            }
            // Para atendentes, mostrar dados gerais do sistema
            // Não aplicar filtro - eles veem todos os tickets
            
            $dailyData[] = [
                'date' => $currentDate->format('Y-m-d'),
                'resolved' => (clone $baseQuery)->whereDate('resolvido_em', $currentDate)->count(),
                'created' => (clone $baseQuery)->whereDate('created_at', $currentDate)->count(),
            ];
            
            $currentDate->addDay();
        }
        
        return [
            'daily_evolution' => $dailyData,
            'status_distribution' => $this->getStatusDistribution($user),
        ];
    }
    
    private function getTeamStats($dateRange)
    {
        $attendants = User::where('tipo', 'atendente')->get();
        $teamStats = [];
        
        foreach ($attendants as $attendant) {
            $resolvedCount = Ticket::where('atendente_id', $attendant->id)
                ->whereBetween('resolvido_em', [$dateRange['start'], $dateRange['end']])
                ->whereNotNull('resolvido_em')
                ->count();
            
            $avgTime = Ticket::where('atendente_id', $attendant->id)
                ->whereBetween('resolvido_em', [$dateRange['start'], $dateRange['end']])
                ->whereNotNull('resolvido_em')
                ->avg('total_time_spent');
            
            $teamStats[] = [
                'name' => $attendant->name,
                'resolved_tickets' => $resolvedCount,
                'avg_resolution_time' => $avgTime ?? 0,
            ];
        }
        
        return $teamStats;
    }
    
    private function getAlerts($user)
    {
        $alerts = [];
        
        // Tickets urgentes
        $urgentQuery = Ticket::where('prioridade', 'Urgente')->whereNull('resolvido_em');
        if ($user->tipo === 'usuario') {
            $urgentQuery->where('usuario_id', $user->id);
        } elseif ($user->tipo === 'atendente') {
            $urgentQuery->where('atendente_id', $user->id);
        }
        $urgentCount = $urgentQuery->count();
        
        if ($urgentCount > 0) {
            $alerts[] = [
                'type' => 'urgent',
                'message' => "Você tem {$urgentCount} ticket(s) urgente(s) pendente(s)",
                'count' => $urgentCount,
            ];
        }
        
        // Tickets há muito tempo sem resposta
        $oldTickets = Ticket::where('updated_at', '<', Carbon::now()->subHours(24))
            ->whereNull('resolvido_em');
        
        if ($user->tipo === 'usuario') {
            $oldTickets->where('usuario_id', $user->id);
        } elseif ($user->tipo === 'atendente') {
            $oldTickets->where('atendente_id', $user->id);
        }
        
        $oldCount = $oldTickets->count();
        
        if ($oldCount > 0) {
            $alerts[] = [
                'type' => 'old',
                'message' => "Você tem {$oldCount} ticket(s) sem atualização há mais de 24 horas",
                'count' => $oldCount,
            ];
        }
        
        return $alerts;
    }
    
    private function getFirstContactResolution($user, $dateRange)
    {
        // Simplificado: tickets resolvidos com apenas uma mensagem
        $baseQuery = Ticket::query();
        
        if ($user->tipo === 'atendente') {
            $baseQuery->where('atendente_id', $user->id);
        }
        
        return $baseQuery
            ->whereBetween('resolvido_em', [$dateRange['start'], $dateRange['end']])
            ->whereNotNull('resolvido_em')
            ->whereIn('id', function ($query) {
                $query->select('ticket_id')
                    ->from('ticket_messages')
                    ->groupBy('ticket_id')
                    ->havingRaw('COUNT(*) = 1');
            })
            ->count();
    }
    
    private function getPriorityDistribution($user)
    {
        $baseQuery = Ticket::query();
        
        if ($user->tipo === 'usuario') {
            $baseQuery->where('usuario_id', $user->id);
        }
        // Para atendentes, mostrar distribuição geral do sistema
        // Não aplicar filtro - eles veem todos os tickets
        
        return $baseQuery->whereNull('resolvido_em')
            ->groupBy('prioridade')
            ->selectRaw('prioridade, COUNT(*) as count')
            ->pluck('count', 'prioridade')
            ->toArray();
    }
    
    private function getCategoryDistribution($user)
    {
        $baseQuery = Ticket::query();
        
        if ($user->tipo === 'usuario') {
            $baseQuery->where('usuario_id', $user->id);
        }
        // Para atendentes, mostrar distribuição geral do sistema
        // Não aplicar filtro - eles veem todos os tickets
        
        return $baseQuery->whereNull('resolvido_em')
            ->join('categories', 'tickets.categoria_id', '=', 'categories.id')
            ->groupBy('categories.nome')
            ->selectRaw('categories.nome, COUNT(*) as count')
            ->pluck('count', 'nome')
            ->toArray();
    }
    
    private function getStatusDistribution($user)
    {
        $baseQuery = Ticket::query();
        
        if ($user->tipo === 'usuario') {
            $baseQuery->where('usuario_id', $user->id);
        }
        // Para atendentes, mostrar distribuição geral do sistema
        // Não aplicar filtro - eles veem todos os tickets
        
        $distribution = [];
        
        $distribution['Aguardando Atendimento'] = (clone $baseQuery)->whereNull('assumed_at')->whereNull('resolvido_em')->count();
        $distribution['Em Atendimento'] = (clone $baseQuery)->whereNotNull('assumed_at')->whereNull('paused_at')->whereNull('resolvido_em')->count();
        $distribution['Pausado'] = (clone $baseQuery)->whereNotNull('paused_at')->whereNull('resolvido_em')->count();
        $distribution['Resolvido'] = (clone $baseQuery)->whereNotNull('resolvido_em')->count();
        
        return $distribution;
    }
}
