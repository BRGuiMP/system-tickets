@extends('layouts.app')

@section('title', 'Tickets')

@push('scripts')
    <script src="{{ asset('js/ticket-filters.js') }}"></script>
@endpush

@section('content')
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Tickets</h2>
            <div class="flex gap-2">
                <a href="{{ route('tickets.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold p-2 rounded" title="Novo Ticket">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </a>
                @if(Auth::user()->tipo === 'atendente')
                    <a href="{{ route('tickets.create-scheduled') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold p-2 rounded" title="Novo Ticket Agendado">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </a>
                @endif
            </div>
        </div>

        <!-- Área de Filtros -->
        <div class="mb-6 filter-container bg-gray-50 p-4 rounded-lg">
            <h3 class="text-lg font-semibold mb-4">Filtros</h3>
            
            <form method="GET" action="{{ route('tickets.index') }}" class="space-y-4">
                <div class="filter-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    <!-- Filtro por Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" id="status" class="filter-input w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Todos os Status</option>
                            <option value="aguardando_atendimento" {{ request('status') == 'aguardando_atendimento' ? 'selected' : '' }}>Aguardando Atendimento</option>
                            <option value="em_atendimento" {{ request('status') == 'em_atendimento' ? 'selected' : '' }}>Em Atendimento</option>
                            <option value="pausado" {{ request('status') == 'pausado' ? 'selected' : '' }}>Pausado</option>
                            <option value="resolvido" {{ request('status') == 'resolvido' ? 'selected' : '' }}>Resolvido</option>
                        </select>
                    </div>

                    <!-- Filtro por Prioridade -->
                    <div>
                        <label for="prioridade" class="block text-sm font-medium text-gray-700 mb-1">Prioridade</label>
                        <select name="prioridade" id="prioridade" class="filter-input w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Todas as Prioridades</option>
                            <option value="Baixa" {{ request('prioridade') == 'Baixa' ? 'selected' : '' }}>Baixa</option>
                            <option value="Média" {{ request('prioridade') == 'Média' ? 'selected' : '' }}>Média</option>
                            <option value="Alta" {{ request('prioridade') == 'Alta' ? 'selected' : '' }}>Alta</option>
                            <option value="Urgente" {{ request('prioridade') == 'Urgente' ? 'selected' : '' }}>Urgente</option>
                        </select>
                    </div>

                    <!-- Filtro por Categoria -->
                    <div>
                        <label for="categoria_id" class="block text-sm font-medium text-gray-700 mb-1">Categoria</label>
                        <select name="categoria_id" id="categoria_id" class="filter-input w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Todas as Categorias</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('categoria_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtro por Atendente -->
                    @if(auth()->user()->tipo === 'atendente')
                        <div>
                            <label for="atendente_id" class="block text-sm font-medium text-gray-700 mb-1">Atendente</label>
                            <select name="atendente_id" id="atendente_id" class="filter-input w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Todos os Atendentes</option>
                                <option value="sem_atendente" {{ request('atendente_id') == 'sem_atendente' ? 'selected' : '' }}>Sem Atendente</option>
                                @foreach($attendants as $attendant)
                                    <option value="{{ $attendant->id }}" {{ request('atendente_id') == $attendant->id ? 'selected' : '' }}>
                                        {{ $attendant->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <!-- Filtro por Título -->
                    <div>
                        <label for="titulo" class="block text-sm font-medium text-gray-700 mb-1">Título</label>
                        <input type="text" name="titulo" id="titulo" value="{{ request('titulo') }}" 
                               placeholder="Buscar por título..." 
                               class="filter-input w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Filtro por Data -->
                    <div>
                        <label for="data_inicio" class="block text-sm font-medium text-gray-700 mb-1">Data de Início</label>
                        <input type="date" name="data_inicio" id="data_inicio" value="{{ request('data_inicio') }}" 
                               class="filter-input w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label for="data_fim" class="block text-sm font-medium text-gray-700 mb-1">Data de Fim</label>
                        <input type="date" name="data_fim" id="data_fim" value="{{ request('data_fim') }}" 
                               class="filter-input w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Filtro por Usuário (apenas para atendentes) -->
                    @if(auth()->user()->tipo === 'atendente')
                        <div>
                            <label for="usuario_id" class="block text-sm font-medium text-gray-700 mb-1">Usuário</label>
                            <select name="usuario_id" id="usuario_id" class="filter-input w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Todos os Usuários</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('usuario_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                <div class="filter-buttons flex justify-between items-center">
                    <div class="flex space-x-2">
                        <button type="submit" class="filter-btn bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Filtrar
                        </button>
                        <a href="{{ route('tickets.index') }}" class="filter-btn bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Limpar Filtros
                        </a>
                    </div>
                    
                    <div class="text-sm text-gray-600">
                        Mostrando {{ $tickets->firstItem() ?? 0 }} - {{ $tickets->lastItem() ?? 0 }} de {{ $tickets->total() }} resultados
                    </div>
                </div>
            </form>
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
                    @forelse($tickets as $ticket)
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
                                {{ $ticket->getFormattedTotalTime() }}
                                @if($ticket->paused_time)
                                    <span class="text-gray-500 text-xs">({{ gmdate('H:i:s', $ticket->paused_time) }} em pausa)</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $ticket->created_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                Nenhum ticket encontrado com os filtros aplicados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $tickets->appends(request()->query())->links() }}
        </div>
    </div>
@endsection
