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
        User::create([
            'name' => 'Atendente Sistema',
            'email' => 'atendente@exemplo.com',
            'password' => Hash::make('senha123'),
            'tipo' => 'atendente',
        ]);

        // Criar usuário comum
        User::create([
            'name' => 'Usuário Teste',
            'email' => 'usuario@exemplo.com',
            'password' => Hash::make('senha123'),
            'tipo' => 'usuario',
        ]);
    }
}
