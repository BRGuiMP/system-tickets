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
    public function index()
    {
        $tickets = Ticket::with(['categoria', 'usuario', 'atendente'])
            ->when(Auth::user()->tipo === 'usuario', function ($query) {
                return $query->where('usuario_id', Auth::id());
            })
            ->latest()
            ->paginate(10);
            
        return view('tickets.index', compact('tickets'));
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
