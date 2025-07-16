@extends('layouts.app')

@section('title', 'Tickets')

@section('content')
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Tickets</h2>
            <a href="{{ route('tickets.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold p-2 rounded" title="Novo Ticket">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoria</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prioridade</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Atendente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tempo de Atendimento</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Criado em</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($tickets as $ticket)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('tickets.show', $ticket) }}" class="text-blue-600 hover:text-blue-900">
                                    #{{ $ticket->id }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $ticket->titulo }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $ticket->categoria->nome }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if(!$ticket->assumed_at) bg-yellow-100 text-yellow-800
                                    @elseif($ticket->isPaused()) bg-orange-100 text-orange-800
                                    @elseif($ticket->isInProgress()) bg-blue-100 text-blue-800
                                    @elseif($ticket->resolvido_em) bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ !$ticket->assumed_at ? 'Aguardando Atendimento' : 
                                       ($ticket->isPaused() ? 'Atendimento Pausado' : 
                                       ($ticket->isInProgress() ? 'Em Atendimento' : 
                                       ($ticket->resolvido_em ? 'Resolvido' : 'Fechado'))) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($ticket->prioridade === 'Baixa') bg-green-100 text-green-800
                                    @elseif($ticket->prioridade === 'Média') bg-yellow-100 text-yellow-800
                                    @elseif($ticket->prioridade === 'Alta') bg-orange-100 text-orange-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ $ticket->prioridade }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $ticket->atendente ? $ticket->atendente->name : 'Não atribuído' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($ticket->total_time_spent)
                                    {{ gmdate('H:i:s', $ticket->total_time_spent) }}
                                    @if($ticket->paused_time)
                                        <span class="text-gray-500 text-xs">({{ gmdate('H:i:s', $ticket->paused_time) }} em pausa)</span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $ticket->created_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $tickets->links() }}
        </div>
    </div>
@endsection
