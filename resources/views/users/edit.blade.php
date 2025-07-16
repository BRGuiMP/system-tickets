@extends('layouts.app')

@section('title', 'Editar Usuário')

@section('content')
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Editar Usuário</h2>
            <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Voltar
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('users.update', $user) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <x-input-label for="name" :value="'Nome'" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name)" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="email" :value="'Email'" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email)" required />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="tipo" :value="'Tipo'" />
                <select id="tipo" name="tipo" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">Selecione o tipo</option>
                    <option value="usuario" {{ old('tipo', $user->tipo) === 'usuario' ? 'selected' : '' }}>Usuário</option>
                    <option value="atendente" {{ old('tipo', $user->tipo) === 'atendente' ? 'selected' : '' }}>Atendente</option>
                </select>
                <x-input-error :messages="$errors->get('tipo')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="password" :value="'Nova Senha (opcional)'" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                <p class="text-sm text-gray-600 mt-1">Deixe em branco se não quiser alterar a senha</p>
            </div>

            <div class="mb-4">
                <x-input-label for="password_confirmation" :value="'Confirmar Nova Senha'" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end">
                <x-primary-button class="ml-4">
                    Atualizar Usuário
                </x-primary-button>
            </div>
        </form>
    </div>
@endsection
