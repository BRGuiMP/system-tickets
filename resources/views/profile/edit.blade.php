@extends('layouts.app')

@section('title', 'Perfil')

@section('content')
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-6">Perfil do Usuário</h2>
        
        <!-- Informações do Perfil -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold mb-4">Informações do Perfil</h3>
            <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
                @csrf
                @method('patch')
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nome</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Salvar
                    </button>
                    @if (session('status') === 'profile-updated')
                        <span class="ml-3 text-sm text-green-600">Salvo com sucesso!</span>
                    @endif
                </div>
            </form>
        </div>
        
        <!-- Alterar Senha -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold mb-4">Alterar Senha</h3>
            <form method="post" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                @method('put')
                
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700">Senha Atual</label>
                    <input type="password" id="current_password" name="current_password" 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           required>
                    @error('current_password', 'updatePassword')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Nova Senha</label>
                    <input type="password" id="password" name="password" 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           required>
                    @error('password', 'updatePassword')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Nova Senha</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           required>
                    @error('password_confirmation', 'updatePassword')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Alterar Senha
                    </button>
                    @if (session('status') === 'password-updated')
                        <span class="ml-3 text-sm text-green-600">Senha alterada com sucesso!</span>
                    @endif
                </div>
            </form>
        </div>
    </div>
@endsection
