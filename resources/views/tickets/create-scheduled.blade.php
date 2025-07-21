@extends('layouts.app')

@section('title', 'Novo Ticket Agendado')

@section('content')
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Novo Ticket Agendado</h2>
            <a href="{{ route('tickets.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Voltar
            </a>
        </div>

        <form action="{{ route('tickets.store-scheduled') }}" method="POST">
            @csrf
            
            <!-- Título -->
            <div class="mb-4">
                <label for="titulo" class="block text-gray-700 text-sm font-bold mb-2">Título</label>
                <input type="text" name="titulo" id="titulo" value="{{ old('titulo') }}" 
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('titulo') border-red-500 @enderror"
                    required>
                @error('titulo')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Descrição -->
            <div class="mb-4">
                <label for="descricao" class="block text-gray-700 text-sm font-bold mb-2">Descrição</label>
                <textarea name="descricao" id="descricao" rows="4" 
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('descricao') border-red-500 @enderror"
                    required>{{ old('descricao') }}</textarea>
                @error('descricao')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- Categoria -->
                <div>
                    <label for="categoria_id" class="block text-gray-700 text-sm font-bold mb-2">Categoria</label>
                    <select name="categoria_id" id="categoria_id" 
                        class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('categoria_id') border-red-500 @enderror"
                        required>
                        <option value="">Selecione uma categoria</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('categoria_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('categoria_id')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Prioridade -->
                <div>
                    <label for="prioridade" class="block text-gray-700 text-sm font-bold mb-2">Prioridade</label>
                    <select name="prioridade" id="prioridade" 
                        class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('prioridade') border-red-500 @enderror"
                        required>
                        <option value="">Selecione a prioridade</option>
                        <option value="Baixa" {{ old('prioridade') == 'Baixa' ? 'selected' : '' }}>Baixa</option>
                        <option value="Média" {{ old('prioridade') == 'Média' ? 'selected' : '' }}>Média</option>
                        <option value="Alta" {{ old('prioridade') == 'Alta' ? 'selected' : '' }}>Alta</option>
                        <option value="Urgente" {{ old('prioridade') == 'Urgente' ? 'selected' : '' }}>Urgente</option>
                    </select>
                    @error('prioridade')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- Usuário -->
                <div>
                    <label for="usuario_id" class="block text-gray-700 text-sm font-bold mb-2">Usuário</label>
                    <select name="usuario_id" id="usuario_id" 
                        class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('usuario_id') border-red-500 @enderror"
                        required>
                        <option value="">Selecione o usuário</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('usuario_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('usuario_id')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Atendente -->
                <div>
                    <label for="atendente_id" class="block text-gray-700 text-sm font-bold mb-2">Atendente</label>
                    <select name="atendente_id" id="atendente_id" 
                        class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('atendente_id') border-red-500 @enderror"
                        required>
                        <option value="">Selecione o atendente</option>
                        @foreach($attendants as $attendant)
                            <option value="{{ $attendant->id }}" {{ old('atendente_id') == $attendant->id ? 'selected' : '' }}>
                                {{ $attendant->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('atendente_id')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <!-- Data do Agendamento -->
                <div>
                    <label for="data_agendamento" class="block text-gray-700 text-sm font-bold mb-2">Data do Agendamento</label>
                    <input type="date" name="data_agendamento" id="data_agendamento" 
                        value="{{ old('data_agendamento', date('Y-m-d')) }}" 
                        min="{{ date('Y-m-d') }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('data_agendamento') border-red-500 @enderror"
                        required>
                    @error('data_agendamento')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Hora do Agendamento -->
                <div>
                    <label for="hora_agendamento" class="block text-gray-700 text-sm font-bold mb-2">Hora do Agendamento</label>
                    <input type="time" name="hora_agendamento" id="hora_agendamento" 
                        value="{{ old('hora_agendamento') }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('hora_agendamento') border-red-500 @enderror"
                        required>
                    @error('hora_agendamento')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Botões -->
            <div class="flex items-center justify-between">
                <a href="{{ route('tickets.index') }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Cancelar
                </a>
                <button type="submit" 
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Criar Ticket Agendado
                </button>
            </div>
        </form>
    </div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validação client-side para data não ser no passado
    const dateInput = document.getElementById('data_agendamento');
    const today = new Date().toISOString().split('T')[0];
    
    dateInput.addEventListener('change', function() {
        if (this.value < today) {
            alert('A data de agendamento não pode ser no passado.');
            this.value = today;
        }
    });
    
    // Auto-foco no primeiro campo
    document.getElementById('titulo').focus();
});
</script>
@endpush
@endsection
