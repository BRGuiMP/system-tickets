@extends('layouts.app')

@section('title', 'Visualizar Ticket')

@section('content')
    <div class="bg-white shadow rounded-lg p-6">
        <div class="mb-6">
            <div class="flex justify-between items-start">
                <h2 class="text-2xl font-bold">Ticket #{{ $ticket->id }} - {{ $ticket->titulo }}</h2>
                @can('update', $ticket)
                    <a href="{{ route('tickets.edit', $ticket) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Editar Ticket
                    </a>
                @endcan
            </div>

            <div class="grid grid-cols-2 gap-4 mt-4">
                <div>
                    <p class="text-gray-600">Status:</p>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                        @if($ticket->status === 'Aberto') bg-yellow-100 text-yellow-800
                        @elseif($ticket->status === 'Em Andamento') bg-blue-100 text-blue-800
                        @elseif($ticket->status === 'Resolvido') bg-green-100 text-green-800
                        @elseif($ticket->status === 'Fechado') bg-gray-100 text-gray-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ $ticket->status }}
                    </span>
                </div>
                <div>
                    <p class="text-gray-600">Prioridade:</p>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                        @if($ticket->prioridade === 'Baixa') bg-green-100 text-green-800
                        @elseif($ticket->prioridade === 'Média') bg-yellow-100 text-yellow-800
                        @elseif($ticket->prioridade === 'Alta') bg-orange-100 text-orange-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ $ticket->prioridade }}
                    </span>
                </div>
                <div>
                    <p class="text-gray-600">Categoria:</p>
                    <p>{{ $ticket->categoria->nome }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Criado por:</p>
                    <p>{{ $ticket->usuario->name }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Atendente:</p>
                    <p>{{ $ticket->atendente ? $ticket->atendente->name : 'Não atribuído' }}</p>
                </div>
            </div>

            @if(auth()->user()->tipo === 'atendente')
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4">Controle de Atendimento</h3>
                    
                    <div class="flex space-x-4">
                        @if(!$ticket->assumed_at)
                            <form action="{{ route('tickets.assume', $ticket) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                    class="inline-flex items-center px-6 py-3 bg-[#22C55E] text-white text-lg font-bold rounded-lg shadow-[0_4px_0_0_#15803D] hover:translate-y-0.5 hover:shadow-[0_2px_0_0_#15803D] active:translate-y-1 active:shadow-none transition-all duration-150"
                                    title="Assumir Ticket">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 mr-2" fill="#ffffff" viewBox="0 0 24 24">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-white">Assumir</span>
                                </button>
                            </form>
                        @elseif($ticket->atendente_id === auth()->id())
                            @if($ticket->isPaused())
                                <form action="{{ route('tickets.resume', $ticket) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                        class="inline-flex items-center px-6 py-3 bg-[#2563EB] text-white text-lg font-bold rounded-lg shadow-[0_4px_0_0_#1D4ED8] hover:translate-y-0.5 hover:shadow-[0_2px_0_0_#1D4ED8] active:translate-y-1 active:shadow-none transition-all duration-150"
                                        title="Retomar Atendimento">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 mr-2" fill="#ffffff" viewBox="0 0 24 24">
                                            <path d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                        </svg>
                                        <span class="text-white">Retomar</span>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('tickets.pause', $ticket) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                        class="inline-flex items-center px-6 py-3 bg-[#F59E0B] text-white text-lg font-bold rounded-lg shadow-[0_4px_0_0_#B45309] hover:translate-y-0.5 hover:shadow-[0_2px_0_0_#B45309] active:translate-y-1 active:shadow-none transition-all duration-150"
                                        title="Pausar Atendimento">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 mr-2" fill="#ffffff" viewBox="0 0 24 24">
                                            <path d="M10 9v6m4-6v6"/>
                                        </svg>
                                        <span class="text-white">Pausar</span>
                                    </button>
                                </form>
                            @endif

                            @if($ticket->isInProgress())
                                <form action="{{ route('tickets.resolve', $ticket) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                        class="inline-flex items-center px-6 py-3 bg-[#7C3AED] text-white text-lg font-bold rounded-lg shadow-[0_4px_0_0_#5B21B6] hover:translate-y-0.5 hover:shadow-[0_2px_0_0_#5B21B6] active:translate-y-1 active:shadow-none transition-all duration-150"
                                        title="Finalizar Atendimento">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 mr-2" fill="#ffffff" viewBox="0 0 24 24">
                                            <path d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span class="text-white">Finalizar</span>
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                    

                    @if($ticket->assumed_at)
                        <div class="mt-4 grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-600">Iniciado em:</p>
                                <p>{{ $ticket->assumed_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Status do Atendimento:</p>
                                <p>{{ $ticket->isPaused() ? 'Pausado' : ($ticket->isInProgress() ? 'Em Andamento' : 'Finalizado') }}</p>
                            </div>
                            @if($ticket->total_time_spent)
                                <div>
                                    <p class="text-gray-600">Tempo Total de Atendimento:</p>
                                    <p>{{ gmdate('H:i:s', $ticket->total_time_spent) }}</p>
                                </div>
                            @endif
                            @if($ticket->paused_time)
                                <div>
                                    <p class="text-gray-600">Tempo Total em Pausa:</p>
                                    <p>{{ gmdate('H:i:s', $ticket->paused_time) }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @endif

            <div class="mt-6">
                <h3 class="text-lg font-semibold mb-4">Descrição</h3>
                <div class="bg-gray-50 rounded p-4">
                    {{ $ticket->descricao }}
                    <p>{{ $ticket->atendente ? $ticket->atendente->name : 'Não atribuído' }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Criado em:</p>
                    <p>{{ $ticket->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            <div class="mt-4">
                <p class="text-gray-600">Descrição:</p>
                <p class="mt-2 whitespace-pre-line">{{ $ticket->descricao }}</p>
            </div>
        </div>

        <div class="mt-8">
            <h3 class="text-xl font-bold mb-4">Mensagens</h3>

            <div class="space-y-4 mb-6">
                @foreach($ticket->mensagens as $mensagem)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-semibold">{{ $mensagem->autor->name }}</p>
                                <p class="text-sm text-gray-500">{{ $mensagem->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            @can('delete', $mensagem)
                                <form action="{{ route('ticket-messages.destroy', $mensagem) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 text-sm"
                                        onclick="return confirm('Tem certeza que deseja excluir esta mensagem?')">
                                        Excluir
                                    </button>
                                </form>
                            @endcan
                        </div>
                        <p class="mt-2 whitespace-pre-line">{{ $mensagem->mensagem }}</p>
                        @if($mensagem->anexo_url)
                            <a href="{{ Storage::url($mensagem->anexo_url) }}" 
                               class="inline-block mt-2 text-blue-600 hover:text-blue-900"
                               target="_blank">
                                📎 Anexo
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>

            <form action="{{ route('ticket-messages.store', ['ticket' => $ticket->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="mensagem" class="block text-gray-700 text-sm font-bold mb-2">Nova Mensagem</label>
                    <textarea name="mensagem" id="mensagem" rows="3" 
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required></textarea>
                </div>

                <div class="mb-4">
                    <label for="anexo" class="block text-gray-700 text-sm font-bold mb-2">Anexo (opcional)</label>
                    <input type="file" name="anexo" id="anexo" 
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <div>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Enviar Mensagem
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
