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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- Data do Agendamento -->
                <div>
                    <label for="data_agendamento" class="block text-gray-700 text-sm font-bold mb-2">Data do Agendamento</label>
                    <input type="date" name="data_agendamento" id="data_agendamento" 
                        value="{{ old('data_agendamento', date('Y-m-d')) }}" 
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

            <!-- Seção de Encerramento (Opcional) -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Encerramento do Ticket (Opcional)</h3>
                <p class="text-sm text-gray-600 mb-4">
                    Se informado, o ticket será criado já como resolvido com o tempo calculado automaticamente.
                </p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Data de Encerramento -->
                    <div>
                        <label for="data_encerramento" class="block text-gray-700 text-sm font-bold mb-2">Data de Encerramento</label>
                        <input type="date" name="data_encerramento" id="data_encerramento" 
                            value="{{ old('data_encerramento') }}" 
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('data_encerramento') border-red-500 @enderror">
                        @error('data_encerramento')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Hora de Encerramento -->
                    <div>
                        <label for="hora_encerramento" class="block text-gray-700 text-sm font-bold mb-2">Hora de Encerramento</label>
                        <input type="time" name="hora_encerramento" id="hora_encerramento" 
                            value="{{ old('hora_encerramento') }}"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('hora_encerramento') border-red-500 @enderror">
                        @error('hora_encerramento')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
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
    // Elementos do DOM
    const dateInput = document.getElementById('data_agendamento');
    const timeInput = document.getElementById('hora_agendamento');
    const endDateInput = document.getElementById('data_encerramento');
    const endTimeInput = document.getElementById('hora_encerramento');
    const today = new Date().toISOString().split('T')[0];
    
    // Validação para data de agendamento não ser no passado
    /*dateInput.addEventListener('change', function() {
        if (this.value < today) {
            alert('A data de agendamento não pode ser no passado.');
            this.value = today;
        }
        validateEndDate();
    });*/
    
    // Validação para hora de agendamento
    timeInput.addEventListener('change', function() {
        validateEndDate();
    });
    
    // Validação para data de encerramento
    endDateInput.addEventListener('change', function() {
        validateEndDate();
    });
    
    // Validação para hora de encerramento
    endTimeInput.addEventListener('change', function() {
        validateEndDate();
    });
    
    // Função para validar data/hora de encerramento
    function validateEndDate() {
        if (!endDateInput.value) return; // Se não há data de encerramento, não validar
        
        const startDate = dateInput.value;
        const startTime = timeInput.value || '00:00';
        const endDate = endDateInput.value;
        const endTime = endTimeInput.value || '23:59';
        
        if (!startDate || !startTime) return;
        
        const startDateTime = new Date(`${startDate}T${startTime}`);
        const endDateTime = new Date(`${endDate}T${endTime}`);
        
        if (endDateTime <= startDateTime) {
            alert('A data/hora de encerramento deve ser posterior à data/hora de agendamento.');
            endDateInput.value = '';
            endTimeInput.value = '';
        }
    }
    
    // Se data de encerramento for preenchida, hora também deve ser
    endDateInput.addEventListener('change', function() {
        if (this.value && !endTimeInput.value) {
            endTimeInput.focus();
        }
    });
    
    // Se hora de encerramento for preenchida, data também deve ser
    endTimeInput.addEventListener('change', function() {
        if (this.value && !endDateInput.value) {
            alert('Para definir hora de encerramento, é necessário informar a data de encerramento.');
            this.value = '';
            endDateInput.focus();
        }
    });
    
    // Auto-foco no primeiro campo
    document.getElementById('titulo').focus();
});
</script>
@endpush
@endsection
