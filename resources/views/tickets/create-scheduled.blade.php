@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Criar Ticket Agendado</h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('tickets.store-scheduled') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="titulo" class="form-label">Título</label>
                            <input type="text" class="form-control @error('titulo') is-invalid @enderror" 
                                   id="titulo" name="titulo" value="{{ old('titulo') }}" required>
                            @error('titulo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição</label>
                            <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                      id="descricao" name="descricao" rows="4" required>{{ old('descricao') }}</textarea>
                            @error('descricao')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="categoria_id" class="form-label">Categoria</label>
                                <select class="form-control @error('categoria_id') is-invalid @enderror" 
                                        id="categoria_id" name="categoria_id" required>
                                    <option value="">Selecione uma categoria</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" 
                                            {{ old('categoria_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->nome }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('categoria_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="prioridade" class="form-label">Prioridade</label>
                                <select class="form-control @error('prioridade') is-invalid @enderror" 
                                        id="prioridade" name="prioridade" required>
                                    <option value="">Selecione a prioridade</option>
                                    <option value="Baixa" {{ old('prioridade') == 'Baixa' ? 'selected' : '' }}>Baixa</option>
                                    <option value="Média" {{ old('prioridade') == 'Média' ? 'selected' : '' }}>Média</option>
                                    <option value="Alta" {{ old('prioridade') == 'Alta' ? 'selected' : '' }}>Alta</option>
                                    <option value="Urgente" {{ old('prioridade') == 'Urgente' ? 'selected' : '' }}>Urgente</option>
                                </select>
                                @error('prioridade')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="usuario_id" class="form-label">Usuário</label>
                                <select class="form-control @error('usuario_id') is-invalid @enderror" 
                                        id="usuario_id" name="usuario_id" required>
                                    <option value="">Selecione o usuário</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" 
                                            {{ old('usuario_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('usuario_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="atendente_id" class="form-label">Atendente</label>
                                <select class="form-control @error('atendente_id') is-invalid @enderror" 
                                        id="atendente_id" name="atendente_id" required>
                                    <option value="">Selecione o atendente</option>
                                    @foreach($attendants as $attendant)
                                        <option value="{{ $attendant->id }}" 
                                            {{ old('atendente_id') == $attendant->id ? 'selected' : '' }}>
                                            {{ $attendant->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('atendente_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="data_agendamento" class="form-label">Data do Agendamento</label>
                                <input type="date" class="form-control @error('data_agendamento') is-invalid @enderror" 
                                       id="data_agendamento" name="data_agendamento" 
                                       value="{{ old('data_agendamento') }}" 
                                       min="{{ date('Y-m-d') }}" required>
                                @error('data_agendamento')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="hora_agendamento" class="form-label">Hora do Agendamento</label>
                                <input type="time" class="form-control @error('hora_agendamento') is-invalid @enderror" 
                                       id="hora_agendamento" name="hora_agendamento" 
                                       value="{{ old('hora_agendamento') }}" required>
                                @error('hora_agendamento')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('tickets.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Criar Ticket Agendado</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
