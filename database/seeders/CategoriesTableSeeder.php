<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'nome' => 'Suporte Técnico',
                'descricao' => 'Problemas técnicos com sistemas, hardware ou software'
            ],
            [
                'nome' => 'Infraestrutura',
                'descricao' => 'Questões relacionadas a servidores, rede ou serviços'
            ],
            [
                'nome' => 'Financeiro',
                'descricao' => 'Dúvidas sobre pagamentos, cobranças ou faturamento'
            ],
            [
                'nome' => 'Dúvidas',
                'descricao' => 'Dúvidas gerais sobre o uso dos sistemas'
            ],
            [
                'nome' => 'Solicitações',
                'descricao' => 'Solicitações de novos recursos ou funcionalidades'
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
