<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DADOS POPULADOS PELOS SEEDERS ===" . PHP_EOL . PHP_EOL;

echo "USUÁRIOS CRIADOS:" . PHP_EOL;
foreach (App\Models\User::all() as $user) {
    echo "- {$user->name} ({$user->email}) - Tipo: {$user->tipo}" . PHP_EOL;
}

echo PHP_EOL . "CATEGORIAS CRIADAS:" . PHP_EOL;
foreach (App\Models\Category::all() as $category) {
    echo "- {$category->nome}: {$category->descricao}" . PHP_EOL;
}

echo PHP_EOL . "RESUMO:" . PHP_EOL;
echo "Total de usuários: " . App\Models\User::count() . PHP_EOL;
echo "Total de categorias: " . App\Models\Category::count() . PHP_EOL;
