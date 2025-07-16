<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Ticket;
use App\Models\TicketMessage;
use Carbon\Carbon;

class DashboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar categorias de teste
        $categories = [
            ['nome' => 'Suporte Técnico', 'descricao' => 'Problemas técnicos e suporte'],
            ['nome' => 'Financeiro', 'descricao' => 'Questões financeiras e pagamentos'],
            ['nome' => 'Vendas', 'descricao' => 'Dúvidas sobre vendas e produtos'],
            ['nome' => 'Recursos Humanos', 'descricao' => 'Questões de RH e pessoal'],
        ];
        
        foreach ($categories as $category) {
            Category::firstOrCreate(['nome' => $category['nome']], $category);
        }
        
        // Criar usuários de teste
        $admin = User::firstOrCreate([
            'email' => 'admin@test.com'
        ], [
            'name' => 'Administrador',
            'password' => bcrypt('password'),
            'tipo' => 'atendente',
        ]);
        
        $atendente1 = User::firstOrCreate([
            'email' => 'atendente1@test.com'
        ], [
            'name' => 'João Silva',
            'password' => bcrypt('password'),
            'tipo' => 'atendente',
        ]);
        
        $atendente2 = User::firstOrCreate([
            'email' => 'atendente2@test.com'
        ], [
            'name' => 'Maria Santos',
            'password' => bcrypt('password'),
            'tipo' => 'atendente',
        ]);
        
        $usuario1 = User::firstOrCreate([
            'email' => 'usuario1@test.com'
        ], [
            'name' => 'Carlos Oliveira',
            'password' => bcrypt('password'),
            'tipo' => 'usuario',
        ]);
        
        $usuario2 = User::firstOrCreate([
            'email' => 'usuario2@test.com'
        ], [
            'name' => 'Ana Costa',
            'password' => bcrypt('password'),
            'tipo' => 'usuario',
        ]);
        
        // Criar tickets de teste
        $categorias = Category::all();
        $atendentes = User::where('tipo', 'atendente')->get();
        $usuarios = User::where('tipo', 'usuario')->get();
        
        $prioridades = ['Baixa', 'Média', 'Alta', 'Urgente'];
        
        // Criar tickets dos últimos 30 dias
        for ($i = 0; $i < 50; $i++) {
            $createdAt = Carbon::now()->subDays(rand(0, 30));
            
            $ticket = Ticket::create([
                'titulo' => 'Ticket de Teste #' . ($i + 1),
                'descricao' => 'Descrição do ticket de teste número ' . ($i + 1),
                'categoria_id' => $categorias->random()->id,
                'prioridade' => $prioridades[array_rand($prioridades)],
                'usuario_id' => $usuarios->random()->id,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
            
            // 70% dos tickets são assumidos por atendentes
            if (rand(1, 100) <= 70) {
                $atendente = $atendentes->random();
                $assumedAt = $createdAt->copy()->addMinutes(rand(5, 120));
                
                $ticket->update([
                    'atendente_id' => $atendente->id,
                    'assumed_at' => $assumedAt,
                ]);
                
                // 60% dos tickets assumidos são resolvidos
                if (rand(1, 100) <= 60) {
                    $resolvidoEm = $assumedAt->copy()->addMinutes(rand(30, 480));
                    $timeSpent = $assumedAt->diffInSeconds($resolvidoEm);
                    
                    $ticket->update([
                        'resolvido_em' => $resolvidoEm,
                        'status' => 'Resolvido',
                        'total_time_spent' => $timeSpent,
                        'updated_at' => $resolvidoEm,
                    ]);
                }
                // 20% dos tickets assumidos estão pausados
                elseif (rand(1, 100) <= 20) {
                    $pausedAt = $assumedAt->copy()->addMinutes(rand(30, 240));
                    $pausedTime = rand(600, 3600); // 10 minutos a 1 hora
                    
                    $ticket->update([
                        'paused_at' => $pausedAt,
                        'paused_time' => $pausedTime,
                        'updated_at' => $pausedAt,
                    ]);
                }
            }
            
            // Adicionar mensagens aos tickets
            $numMessages = rand(1, 5);
            for ($j = 0; $j < $numMessages; $j++) {
                $messageCreatedAt = $ticket->created_at->copy()->addMinutes(rand(5, 1440));
                
                // Definir autor da mensagem
                $autorId = $ticket->atendente_id ?? $ticket->usuario_id;
                if (rand(1, 2) == 1 && $ticket->usuario_id) {
                    $autorId = $ticket->usuario_id;
                }
                
                TicketMessage::create([
                    'ticket_id' => $ticket->id,
                    'autor_id' => $autorId,
                    'mensagem' => 'Mensagem de teste #' . ($j + 1) . ' para o ticket #' . $ticket->id,
                    'created_at' => $messageCreatedAt,
                    'updated_at' => $messageCreatedAt,
                ]);
            }
        }
        
        $this->command->info('Dados de teste para dashboard criados com sucesso!');
    }
}
