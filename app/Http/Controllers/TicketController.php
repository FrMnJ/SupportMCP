<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with(['client', 'assignedTo', 'system'])->get();
        return Inertia::render('Tickets/Index', ['tickets' => $tickets]);
    }

    public function show(Ticket $ticket)
    {
        return Inertia::render('TicketDetail', ['ticket' => $ticket->load(['client', 'assignedTo'])]);
    }

    public function create()
    {
        return Inertia::render('CreateTicket');
    }

    public function store(Request $request)
    {
        $request->validate([
            'issue' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'category' => 'required|in:bug,feature_request,question,other',
            'client_id' => 'required|exists:users,id',
        ]);

        Ticket::create($request->all());

        return redirect()->route('tickets.index')->with('success', 'Ticket creado correctamente.');
    }

    public function edit(Ticket $ticket)
    {
        return Inertia::render('EditTicket', ['ticket' => $ticket]);
    }

    public function update(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed,pending',
            'priority' => 'required|in:low,medium,high',
        ]);

        $ticket->update($request->all());

        return redirect()->route('tickets.index')->with('success', 'Ticket actualizado correctamente.');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        return redirect()->route('tickets.index')->with('success', 'Ticket eliminado correctamente.');
    }
}
