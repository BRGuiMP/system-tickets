<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Assume um ticket para atendimento
     */
    public function assume(Ticket $ticket)
    {
        if (auth()->user()->tipo !== 'atendente') {
            return redirect()->back()->with('error', 'Apenas atendentes podem assumir tickets.');
        }

        $ticket->assume();
        return redirect()->back()->with('success', 'Ticket assumido com sucesso.');
    }

    /**
     * Pausa o atendimento de um ticket
     */
    public function pause(Ticket $ticket)
    {
        if (auth()->id() !== $ticket->atendente_id) {
            return redirect()->back()->with('error', 'Você não é o atendente deste ticket.');
        }

        $ticket->pause();
        return redirect()->back()->with('success', 'Atendimento pausado com sucesso.');
    }

    /**
     * Retoma o atendimento de um ticket
     */
    public function resume(Ticket $ticket)
    {
        if (auth()->id() !== $ticket->atendente_id) {
            return redirect()->back()->with('error', 'Você não é o atendente deste ticket.');
        }

        $ticket->resume();
        return redirect()->back()->with('success', 'Atendimento retomado com sucesso.');
    }

    /**
     * Finaliza o atendimento de um ticket
     */
    public function resolve(Ticket $ticket)
    {
        if (auth()->id() !== $ticket->atendente_id) {
            return redirect()->back()->with('error', 'Você não é o atendente deste ticket.');
        }

        $ticket->resolve();
        return redirect()->back()->with('success', 'Ticket resolvido com sucesso.');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Ticket::with(['categoria', 'usuario', 'atendente']);
        
        // Filtro por tipo de usuário
        if (Auth::user()->tipo === 'usuario') {
            $query->where('usuario_id', Auth::id());
        }
        
        // Filtro por status
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'aguardando_atendimento':
                    $query->whereNull('assumed_at');
                    break;
                case 'em_atendimento':
                    $query->whereNotNull('assumed_at')
                          ->whereNull('paused_at')
                          ->whereNull('resolvido_em');
                    break;
                case 'pausado':
                    $query->whereNotNull('paused_at')
                          ->whereNull('resolvido_em');
                    break;
                case 'resolvido':
                    $query->whereNotNull('resolvido_em');
                    break;
            }
        }
        
        // Filtro por prioridade
        if ($request->filled('prioridade')) {
            $query->where('prioridade', $request->prioridade);
        }
        
        // Filtro por categoria
        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }
        
        // Filtro por atendente
        if ($request->filled('atendente_id')) {
            if ($request->atendente_id === 'sem_atendente') {
                $query->whereNull('atendente_id');
            } else {
                $query->where('atendente_id', $request->atendente_id);
            }
        }
        
        // Filtro por título
        if ($request->filled('titulo')) {
            $query->where('titulo', 'like', '%' . $request->titulo . '%');
        }
        
        // Filtro por data de início
        if ($request->filled('data_inicio')) {
            $query->whereDate('created_at', '>=', $request->data_inicio);
        }
        
        // Filtro por data de fim
        if ($request->filled('data_fim')) {
            $query->whereDate('created_at', '<=', $request->data_fim);
        }
        
        // Filtro por usuário (apenas para atendentes)
        if (Auth::user()->tipo === 'atendente' && $request->filled('usuario_id')) {
            $query->where('usuario_id', $request->usuario_id);
        }
        
        $tickets = $query->latest()->paginate(10);
        
        // Buscar dados para os filtros
        $categories = Category::orderBy('nome')->get();
        $attendants = \App\Models\User::where('tipo', 'atendente')->orderBy('name')->get();
        $users = \App\Models\User::where('tipo', 'usuario')->orderBy('name')->get();
            
        return view('tickets.index', compact('tickets', 'categories', 'attendants', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $users = null;
        if (Auth::user()->tipo === 'atendente') {
            $users = \App\Models\User::whereIn('tipo', ['usuario','atendente'])->orderBy('name')->get();
        }
        return view('tickets.create', compact('categories', 'users'));
    }

    /**
     * Show the form for creating a scheduled ticket (atendentes only).
     */
    public function createScheduled()
    {
        if (Auth::user()->tipo !== 'atendente') {
            abort(403, 'Apenas atendentes podem criar tickets agendados.');
        }
        
        $categories = Category::all();
        $users = \App\Models\User::where('tipo', 'usuario')->orderBy('name')->get();
        $attendants = \App\Models\User::where('tipo', 'atendente')->orderBy('name')->get();
        
        return view('tickets.create-scheduled', compact('categories', 'users', 'attendants'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'categoria_id' => 'required|exists:categories,id',
            'prioridade' => 'required|in:Baixa,Média,Alta,Urgente',
        ]);

        $usuarioId = Auth::id();
        if (Auth::user()->tipo === 'atendente' && $request->filled('usuario_id')) {
            $usuarioId = $request->usuario_id;
        }

        $ticket = Ticket::create([
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'categoria_id' => $request->categoria_id,
            'prioridade' => $request->prioridade,
            'status' => 'Aberto',
            'usuario_id' => $usuarioId
        ]);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Ticket criado com sucesso.');
    }

    /**
     * Store a newly created scheduled ticket.
     */
    public function storeScheduled(Request $request)
    {
        if (Auth::user()->tipo !== 'atendente') {
            abort(403, 'Apenas atendentes podem criar tickets agendados.');
        }

        $rules = [
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'categoria_id' => 'required|exists:categories,id',
            'prioridade' => 'required|in:Baixa,Média,Alta,Urgente',
            'usuario_id' => 'required|exists:users,id',
            'atendente_id' => 'required|exists:users,id',
            'data_agendamento' => 'required|date',
            'hora_agendamento' => 'required|date_format:H:i',
            'data_encerramento' => 'nullable|date',
            'hora_encerramento' => 'nullable|date_format:H:i',
        ];

        // Se data de encerramento for informada, hora também deve ser
        if ($request->filled('data_encerramento')) {
            $rules['hora_encerramento'] = 'required|date_format:H:i';
        }

        $request->validate($rules);

        // Combinar data e hora de agendamento
        $dataHoraAgendamento = $request->data_agendamento . ' ' . $request->hora_agendamento . ':00';

        // Verificar se há data de encerramento
        $dataHoraEncerramento = null;
        if ($request->filled('data_encerramento') && $request->filled('hora_encerramento')) {
            $dataHoraEncerramento = $request->data_encerramento . ' ' . $request->hora_encerramento . ':00';
            
            // Validar que encerramento é posterior ao agendamento
            if (strtotime($dataHoraEncerramento) <= strtotime($dataHoraAgendamento)) {
                return back()->withErrors([
                    'data_encerramento' => 'A data/hora de encerramento deve ser posterior à data/hora de agendamento.'
                ])->withInput();
            }
        }

        // Determinar status baseado na presença de data de encerramento
        $status = $dataHoraEncerramento ? 'Resolvido' : 'Em Andamento';

        // Criar o ticket
        $ticketData = [
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'categoria_id' => $request->categoria_id,
            'prioridade' => $request->prioridade,
            'status' => $status,
            'usuario_id' => $request->usuario_id,
            'atendente_id' => $request->atendente_id,
            'assumed_at' => now(),
            'data_agendamento' => $dataHoraAgendamento,
        ];

        // Se há data de encerramento, calcular tempo e definir como resolvido
        if ($dataHoraEncerramento) {
            $ticketData['resolvido_em'] = $dataHoraEncerramento;
            
            // Calcular tempo total gasto (em segundos)
            $inicio = strtotime($dataHoraAgendamento);
            $fim = strtotime($dataHoraEncerramento);
            $ticketData['total_time_spent'] = $fim - $inicio;
        }

        $ticket = Ticket::create($ticketData);

        $message = $dataHoraEncerramento 
            ? 'Ticket agendado criado e resolvido com sucesso. Tempo calculado automaticamente.'
            : 'Ticket agendado criado e assumido com sucesso.';

        return redirect()->route('tickets.show', $ticket)
            ->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        $ticket->load(['categoria', 'usuario', 'atendente', 'mensagens.autor']);
        return view('tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        $this->authorize('update', $ticket);
        $categories = Category::all();
        return view('tickets.edit', compact('ticket', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket);
        
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'categoria_id' => 'required|exists:categories,id',
            'prioridade' => 'required|in:Baixa,Média,Alta,Urgente',
            'status' => 'required|in:Aberto,Em Andamento,Resolvido,Fechado,Cancelado'
        ]);

        $data = $request->all();
        if ($request->status === 'Resolvido' && $ticket->status !== 'Resolvido') {
            $data['resolvido_em'] = now();
        }

        if (Auth::user()->tipo === 'atendente' && !$ticket->atendente_id) {
            $data['atendente_id'] = Auth::id();
        }

        $ticket->update($data);
        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Ticket atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        $this->authorize('delete', $ticket);
        $ticket->delete();
        return redirect()->route('tickets.index')
            ->with('success', 'Ticket excluído com sucesso.');
    }
}
