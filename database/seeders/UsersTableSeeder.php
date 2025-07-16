<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar usuário atendente
        User::firstOrCreate([
            'email' => 'atendente@exemplo.com'
        ], [
            'name' => 'Atendente Sistema',
            'password' => Hash::make('senha123'),
            'tipo' => 'atendente',
        ]);

        // Criar usuário comum
        User::firstOrCreate([
            'email' => 'usuario@exemplo.com'
        ], [
            'name' => 'Usuário Teste',
            'password' => Hash::make('senha123'),
            'tipo' => 'usuario',
        ]);
    }
}
