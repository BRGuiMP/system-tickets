@extends('layouts.app')

@section('title', 'Editar Ticket')

@section('content')
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-4">Editar Ticket #{{ $ticket->id }}</h2>

        <form action="{{ route('tickets.update', $ticket) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="titulo" class="block text-gray-700 text-sm font-bold mb-2">Título</label>
                <input type="text" name="titulo" id="titulo" value="{{ old('titulo', $ticket->titulo) }}" 
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    required>
            </div>

            <div class="mb-4">
                <label for="categoria_id" class="block text-gray-700 text-sm font-bold mb-2">Categoria</label>
                <select name="categoria_id" id="categoria_id" 
                    class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('categoria_id', $ticket->categoria_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                <select name="status" id="status" 
                    class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    required>
                    <option value="Aberto" {{ old('status', $ticket->status) == 'Aberto' ? 'selected' : '' }}>Aberto</option>
                    <option value="Em Andamento" {{ old('status', $ticket->status) == 'Em Andamento' ? 'selected' : '' }}>Em Andamento</option>
                    <option value="Resolvido" {{ old('status', $ticket->status) == 'Resolvido' ? 'selected' : '' }}>Resolvido</option>
                    <option value="Fechado" {{ old('status', $ticket->status) == 'Fechado' ? 'selected' : '' }}>Fechado</option>
                    <option value="Cancelado" {{ old('status', $ticket->status) == 'Cancelado' ? 'selected' : '' }}>Cancelado</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="prioridade" class="block text-gray-700 text-sm font-bold mb-2">Prioridade</label>
                <select name="prioridade" id="prioridade" 
                    class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    required>
                    <option value="Baixa" {{ old('prioridade', $ticket->prioridade) == 'Baixa' ? 'selected' : '' }}>Baixa</option>
                    <option value="Média" {{ old('prioridade', $ticket->prioridade) == 'Média' ? 'selected' : '' }}>Média</option>
                    <option value="Alta" {{ old('prioridade', $ticket->prioridade) == 'Alta' ? 'selected' : '' }}>Alta</option>
                    <option value="Urgente" {{ old('prioridade', $ticket->prioridade) == 'Urgente' ? 'selected' : '' }}>Urgente</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="descricao" class="block text-gray-700 text-sm font-bold mb-2">Descrição</label>
                <textarea name="descricao" id="descricao" rows="5" 
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    required>{{ old('descricao', $ticket->descricao) }}</textarea>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Atualizar Ticket
                </button>
                <a href="{{ route('tickets.show', $ticket) }}" class="text-gray-600 hover:text-gray-800">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
