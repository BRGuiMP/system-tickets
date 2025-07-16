@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<style>
    .metric-card {
        transition: transform 0.2s;
    }
    .metric-card:hover {
        transform: translateY(-2px);
    }
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
    .alert-urgent {
        background-color: #fef2f2;
        border-color: #fca5a5;
        color: #dc2626;
    }
    .alert-old {
        background-color: #fffbeb;
        border-color: #fbbf24;
        color: #d97706;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/dashboard.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfico de evolução diária
    const dailyCtx = document.getElementById('dailyEvolutionChart').getContext('2d');
    const dailyData = @json($chartData['daily_evolution']);
    
    new Chart(dailyCtx, {
        type: 'line',
        data: {
            labels: dailyData.map(d => new Date(d.date).toLocaleDateString('pt-BR')),
            datasets: [{
                label: 'Tickets Resolvidos',
                data: dailyData.map(d => d.resolved),
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                tension: 0.1
            }, {
                label: 'Tickets Criados',
                data: dailyData.map(d => d.created),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    
    // Gráfico de distribuição de status
    const statusCtx = document.getElementById('statusDistributionChart').getContext('2d');
    const statusData = @json($chartData['status_distribution']);
    
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(statusData),
            datasets: [{
                data: Object.values(statusData),
                backgroundColor: [
                    '#fbbf24',
                    '#3b82f6',
                    '#f97316',
                    '#22c55e'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                }
            }
        }
    });
    
    // Gráfico de distribuição por prioridade
    const priorityCtx = document.getElementById('priorityDistributionChart').getContext('2d');
    const priorityData = @json($workload['distribuicao_prioridade']);
    
    new Chart(priorityCtx, {
        type: 'bar',
        data: {
            labels: Object.keys(priorityData),
            datasets: [{
                label: 'Tickets por Prioridade',
                data: Object.values(priorityData),
                backgroundColor: [
                    '#22c55e',
                    '#fbbf24',
                    '#f97316',
                    '#ef4444'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Cabeçalho do Dashboard -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
                <p class="text-gray-600">Bem-vindo, {{ auth()->user()->name }}!</p>
            </div>
            
            <!-- Filtro de Período -->
            <div class="flex items-center space-x-4">
                <a href="{{ route('dashboard.help') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Ajuda
                </a>
                <form method="GET" action="{{ route('dashboard') }}" class="flex items-center space-x-2">
                    <select name="period" id="period" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="this.form.submit()">
                        <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Hoje</option>
                        <option value="week" {{ $period == 'week' ? 'selected' : '' }}>Esta Semana</option>
                        <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Este Mês</option>
                        <option value="custom" {{ $period == 'custom' ? 'selected' : '' }}>Personalizado</option>
                    </select>
                </form>
            </div>
        </div>
    </div>

    <!-- Alertas -->
    @if(count($alerts) > 0)
        <div class="space-y-2">
            @foreach($alerts as $alert)
                <div class="p-4 rounded-lg border-l-4 {{ $alert['type'] == 'urgent' ? 'alert-urgent' : 'alert-old' }}">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            @if($alert['type'] == 'urgent')
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            @else
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium">{{ $alert['message'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Cards de Estatísticas Gerais -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <div class="metric-card bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total de Tickets</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_tickets'] }}</p>
                </div>
            </div>
        </div>

        <div class="metric-card bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-green-500 text-white">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Resolvidos</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['tickets_resolvidos'] }}</p>
                </div>
            </div>
        </div>

        <div class="metric-card bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Em Atendimento</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['tickets_em_atendimento'] }}</p>
                </div>
            </div>
        </div>

        <div class="metric-card bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-orange-500 text-white">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Pausados</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['tickets_pausados'] }}</p>
                </div>
            </div>
        </div>

        <div class="metric-card bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-yellow-500 text-white">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Aguardando</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['tickets_aguardando'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Cards de Performance -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="metric-card bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-purple-500 text-white">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Tempo Médio</p>
                    <p class="text-2xl font-bold text-gray-900">{{ gmdate('H:i:s', $performance['tempo_medio_resolucao']) }}</p>
                </div>
            </div>
        </div>

        <div class="metric-card bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Tempo Total</p>
                    <p class="text-2xl font-bold text-gray-900">{{ gmdate('H:i:s', $performance['tempo_total_hoje']) }}</p>
                </div>
            </div>
        </div>

        <div class="metric-card bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-green-500 text-white">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Taxa de Resolução</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($performance['taxa_resolucao'], 1) }}%</p>
                </div>
            </div>
        </div>

        <div class="metric-card bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-teal-500 text-white">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Primeiro Contato</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $performance['tickets_primeiro_contato'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Gráfico de Evolução Diária -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4">Evolução dos Tickets</h3>
            <div class="chart-container">
                <canvas id="dailyEvolutionChart"></canvas>
            </div>
        </div>

        <!-- Gráfico de Distribuição de Status -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4">Distribuição por Status</h3>
            <div class="chart-container">
                <canvas id="statusDistributionChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Gráficos de Workload -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Gráfico de Prioridade -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4">Tickets por Prioridade</h3>
            <div class="chart-container">
                <canvas id="priorityDistributionChart"></canvas>
            </div>
        </div>

        <!-- Estatísticas da Equipe -->
        @if(auth()->user()->tipo === 'atendente' && count($teamStats) > 0)
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Ranking da Equipe</h3>
                <div class="space-y-3">
                    @foreach($teamStats as $index => $member)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                    {{ $index + 1 }}
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $member['name'] }}</p>
                                    <p class="text-xs text-gray-500">{{ $member['resolved_tickets'] }} tickets resolvidos</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">{{ gmdate('H:i:s', $member['avg_resolution_time']) }}</p>
                                <p class="text-xs text-gray-500">Tempo médio</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <!-- Distribuição por Categoria -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Tickets por Categoria</h3>
                <div class="space-y-3">
                    @foreach($workload['distribuicao_categoria'] as $categoria => $count)
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-900">{{ $categoria }}</span>
                            <span class="text-sm text-gray-500">{{ $count }} tickets</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Ações Rápidas -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-semibold mb-4">Ações Rápidas</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('tickets.create') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-900">Criar Novo Ticket</p>
                    <p class="text-xs text-gray-500">Abrir um novo chamado</p>
                </div>
            </a>

            <a href="{{ route('tickets.index', ['status' => 'aguardando_atendimento']) }}" class="flex items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-900">Tickets Aguardando</p>
                    <p class="text-xs text-gray-500">{{ $stats['tickets_aguardando'] }} tickets disponíveis</p>
                </div>
            </a>

            <a href="{{ route('tickets.index', ['prioridade' => 'Urgente']) }}" class="flex items-center p-4 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-900">Tickets Urgentes</p>
                    <p class="text-xs text-gray-500">Ver todos os urgentes</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
