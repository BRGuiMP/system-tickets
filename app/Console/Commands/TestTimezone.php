<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestTimezone extends Command
{
    protected $signature = 'test:timezone';
    protected $description = 'Testa o timezone atual do Laravel';

    public function handle()
    {
        $this->info('Timezone configurado: ' . config('app.timezone'));
        $this->info('Horário atual (now()): ' . now());
        $this->info('Horário atual (Carbon::now()): ' . \Carbon\Carbon::now());
        $this->info('Timezone da instância Carbon: ' . now()->timezone->getName());
    }
}
