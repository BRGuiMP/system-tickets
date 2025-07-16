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

echo PHP_EOL . "TICKETS CRIADOS:" . PHP_EOL;
$tickets = App\Models\Ticket::with(['categoria', 'usuario', 'atendente'])->get();
foreach ($tickets as $ticket) {
    $atendente = $ticket->atendente ? $ticket->atendente->name : 'Não atribuído';
    echo "- #{$ticket->id} - {$ticket->titulo} (Prioridade: {$ticket->prioridade}) - Categoria: {$ticket->categoria->nome} - Usuário: {$ticket->usuario->name} - Atendente: {$atendente} - Status: {$ticket->status}" . PHP_EOL;
}

echo PHP_EOL . "MENSAGENS CRIADAS:" . PHP_EOL;
$messages = App\Models\TicketMessage::with(['ticket', 'autor'])->get();
foreach ($messages as $message) {
    echo "- Ticket #{$message->ticket->id} - Autor: {$message->autor->name} - Mensagem: " . substr($message->mensagem, 0, 50) . "..." . PHP_EOL;
}

echo PHP_EOL . "RESUMO:" . PHP_EOL;
echo "Total de usuários: " . App\Models\User::count() . PHP_EOL;
echo "Total de categorias: " . App\Models\Category::count() . PHP_EOL;
echo "Total de tickets: " . App\Models\Ticket::count() . PHP_EOL;
echo "Total de mensagens: " . App\Models\TicketMessage::count() . PHP_EOL;

// Estatísticas dos tickets
$statusCounts = App\Models\Ticket::select('status', \DB::raw('count(*) as total'))
    ->groupBy('status')
    ->pluck('total', 'status')
    ->toArray();

echo PHP_EOL . "ESTATÍSTICAS DOS TICKETS:" . PHP_EOL;
foreach ($statusCounts as $status => $count) {
    echo "- {$status}: {$count} tickets" . PHP_EOL;
}

$prioridadeCounts = App\Models\Ticket::select('prioridade', \DB::raw('count(*) as total'))
    ->groupBy('prioridade')
    ->pluck('total', 'prioridade')
    ->toArray();

echo PHP_EOL . "TICKETS POR PRIORIDADE:" . PHP_EOL;
foreach ($prioridadeCounts as $prioridade => $count) {
    echo "- {$prioridade}: {$count} tickets" . PHP_EOL;
}
