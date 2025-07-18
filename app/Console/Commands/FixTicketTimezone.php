<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ticket;

class FixTicketTimezone extends Command
{
    protected $signature = 'ticket:fix-timezone {id}';
    protected $description = 'Corrige o timezone de um ticket específico';

    public function handle()
    {
        $ticketId = $this->argument('id');
        $ticket = Ticket::find($ticketId);

        if (!$ticket) {
            $this->error("Ticket #{$ticketId} não encontrado.");
            return;
        }

        $this->info("Horário atual do ticket #{$ticketId}: {$ticket->created_at}");
        
        // Corrige o horário subtraindo 3 horas (diferença UTC para Brasil)
        $newTime = $ticket->created_at->subHours(3);
        $ticket->created_at = $newTime;
        $ticket->save();

        $this->info("Novo horário do ticket #{$ticketId}: {$ticket->created_at}");
        $this->info("Ticket atualizado com sucesso!");
    }
}
