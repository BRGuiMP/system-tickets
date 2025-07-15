<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TicketMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort(404);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Ticket $ticket)
    {
        $request->validate([
            'mensagem' => 'required|string',
            'anexo' => 'nullable|file|max:10240' // máximo 10MB
        ]);

        $message = new TicketMessage([
            'mensagem' => $request->mensagem,
            'autor_id' => Auth::id()
        ]);

        if ($request->hasFile('anexo')) {
            $path = $request->file('anexo')->store('anexos/tickets', 'public');
            $message->anexo_url = $path;
        }

        $ticket->mensagens()->save($message);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Mensagem adicionada com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TicketMessage $message)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TicketMessage $message)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TicketMessage $message)
    {
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TicketMessage $message)
    {
        $this->authorize('delete', $message);
        
        if ($message->anexo_url) {
            Storage::disk('public')->delete($message->anexo_url);
        }
        
        $ticket = $message->ticket;
        $message->delete();
        
        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Mensagem excluída com sucesso.');
    }
}
