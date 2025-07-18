<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->boot();

use App\Models\Ticket;

$ticket = Ticket::find(101);

if ($ticket) {
    $ticket->created_at = '2025-07-18 16:21:00';
    $ticket->save();
    echo "Ticket #101 atualizado com sucesso!\n";
    echo "Nova data de criação: " . $ticket->created_at . "\n";
} else {
    echo "Ticket #101 não encontrado.\n";
}
