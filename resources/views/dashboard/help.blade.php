@extends('layouts.app')

@section('title', 'Ajuda do Dashboard')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow rounded-lg p-6">
        <h1 class="text-3xl font-bold mb-6">Ajuda do Dashboard</h1>
        
        <div class="space-y-8">
            <!-- Indicadores Gerais -->
            <div>
                <h2 class="text-2xl font-semibold mb-4 text-blue-600">Indicadores Gerais</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-lg mb-2">Total de Tickets</h3>
                        <p class="text-gray-600">Número total de tickets criados no período selecionado.</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-lg mb-2">Tickets Resolvidos</h3>
                        <p class="text-gray-600">Número de tickets que foram resolvidos no período selecionado.</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-lg mb-2">Em Atendimento</h3>
                        <p class="text-gray-600">Tickets que estão sendo atendidos no momento (assumidos e não pausados).</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-lg mb-2">Pausados</h3>
                        <p class="text-gray-600">Tickets que foram pausados pelo atendente temporariamente.</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-lg mb-2">Aguardando Atendimento</h3>
                        <p class="text-gray-600">Tickets que ainda não foram assumidos por nenhum atendente.</p>
                    </div>
                </div>
            </div>

            <!-- Indicadores de Performance -->
            <div>
                <h2 class="text-2xl font-semibold mb-4 text-green-600">Indicadores de Performance</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-lg mb-2">Tempo Médio de Resolução</h3>
                        <p class="text-gray-600">Tempo médio que leva para resolver um ticket desde que foi assumido.</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-lg mb-2">Tempo Total de Atendimento</h3>
                        <p class="text-gray-600">Soma total do tempo gasto em atendimentos no período.</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-lg mb-2">Taxa de Resolução</h3>
                        <p class="text-gray-600">Percentual de tickets resolvidos em relação aos assumidos.</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-lg mb-2">Primeiro Contato</h3>
                        <p class="text-gray-600">Número de tickets resolvidos com apenas uma mensagem (alta eficiência).</p>
                    </div>
                </div>
            </div>

            <!-- Gráficos -->
            <div>
                <h2 class="text-2xl font-semibold mb-4 text-purple-600">Gráficos e Visualizações</h2>
                <div class="space-y-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-lg mb-2">Evolução dos Tickets</h3>
                        <p class="text-gray-600">Gráfico de linha mostrando a evolução diária dos tickets criados e resolvidos.</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-lg mb-2">Distribuição por Status</h3>
                        <p class="text-gray-600">Gráfico de rosca mostrando a distribuição dos tickets por status atual.</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-lg mb-2">Tickets por Prioridade</h3>
                        <p class="text-gray-600">Gráfico de barras mostrando quantos tickets existem em cada nível de prioridade.</p>
                    </div>
                </div>
            </div>

            <!-- Alertas -->
            <div>
                <h2 class="text-2xl font-semibold mb-4 text-red-600">Sistema de Alertas</h2>
                <div class="space-y-4">
                    <div class="bg-red-50 p-4 rounded-lg border-l-4 border-red-500">
                        <h3 class="font-semibold text-lg mb-2">Tickets Urgentes</h3>
                        <p class="text-gray-600">Alertas sobre tickets com prioridade "Urgente" que precisam de atenção imediata.</p>
                    </div>
                    <div class="bg-yellow-50 p-4 rounded-lg border-l-4 border-yellow-500">
                        <h3 class="font-semibold text-lg mb-2">Tickets Antigos</h3>
                        <p class="text-gray-600">Alertas sobre tickets que não receberam atualização há mais de 24 horas.</p>
                    </div>
                </div>
            </div>

            <!-- Filtros de Período -->
            <div>
                <h2 class="text-2xl font-semibold mb-4 text-indigo-600">Filtros de Período</h2>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-600 mb-2">Use os filtros de período para visualizar dados específicos:</p>
                    <ul class="list-disc list-inside space-y-1 text-gray-600">
                        <li><strong>Hoje:</strong> Dados apenas do dia atual</li>
                        <li><strong>Esta Semana:</strong> Dados dos últimos 7 dias</li>
                        <li><strong>Este Mês:</strong> Dados do mês atual</li>
                        <li><strong>Personalizado:</strong> Selecione um período específico</li>
                    </ul>
                </div>
            </div>

            <!-- Ações Rápidas -->
            <div>
                <h2 class="text-2xl font-semibold mb-4 text-gray-600">Ações Rápidas</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-lg mb-2">Criar Novo Ticket</h3>
                        <p class="text-gray-600">Atalho para criar um novo ticket rapidamente.</p>
                    </div>
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-lg mb-2">Tickets Aguardando</h3>
                        <p class="text-gray-600">Acesso direto aos tickets que aguardam atendimento.</p>
                    </div>
                    <div class="bg-red-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-lg mb-2">Tickets Urgentes</h3>
                        <p class="text-gray-600">Filtro direto para tickets com prioridade urgente.</p>
                    </div>
                </div>
            </div>

            <!-- Dicas de Uso -->
            <div>
                <h2 class="text-2xl font-semibold mb-4 text-gray-600">Dicas de Uso</h2>
                <div class="bg-blue-50 p-4 rounded-lg">
                    <ul class="list-disc list-inside space-y-2 text-gray-600">
                        <li>O dashboard atualiza automaticamente a cada 5 minutos</li>
                        <li>Clique nos cards de métricas para obter mais detalhes</li>
                        <li>Use os filtros de período para análises específicas</li>
                        <li>Mantenha atenção aos alertas para priorizar tarefas</li>
                        <li>O relógio em tempo real ajuda a controlar o tempo</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="mt-8 text-center">
            <a href="{{ route('dashboard') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Voltar ao Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
