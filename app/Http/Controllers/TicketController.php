<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\System;
use Carbon\Carbon;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with(['client', 'assignedTo', 'system'])->get();
        return Inertia::render('Tickets/Index', ['tickets' => $tickets]);
    }

    public function show(Ticket $ticket)
    {
        return Inertia::render('Tickets/Show', ['ticket' => $ticket->load(['client', 'assignedTo', 'system'])]);
    }

    public function create()
    {
        return Inertia::render('Tickets/Create', [
            'clients' => User::role('client')->get(),
            'systems' => System::all(),
            'users' => User::withoutRole(['client', 'agent'])->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'issue' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'category' => 'required|in:bug,feature_request,question,other',
            'client_id' => 'required|exists:users,id',
            'system_id' => 'required|exists:systems,id',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|in:open,in_progress,resolved,closed,pending',
            'resolution' => 'nullable|string',
            'comments' => 'nullable|string',
            'score' => 'nullable|integer',
        ]);

        $ticketData = $request->all();

        if ($request->filled('assigned_to')) {
            $ticketData['assigned_at'] = Carbon::now();
        }

        Ticket::create($ticketData);

        return redirect()->route('tickets.index')->with('success', 'Ticket creado correctamente.');
    }

    public function edit(Ticket $ticket)
    {
        return Inertia::render('Tickets/Edit', [
            'ticket' => $ticket->load(['client', 'assignedTo', 'system']),
            'clients' => User::role('client')->get(),
            'systems' => System::all(),
            'users' => User::withoutRole(['client', 'agent'])->get(),
        ]);
    }

    public function update(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed,pending',
            'priority' => 'required|in:low,medium,high',
            'resolution' => 'nullable|string',
        ]);

        $ticketData = $request->all();

        if($request->status == 'resolved') {
            $ticketData['resolved_at'] = Carbon::now();
        }
        else{
            $ticketData['resolved_at'] = null;
        }

        if($request->filled('assigned_to')) {
            $ticketData['assigned_at'] = Carbon::now();
        }else{
            $ticketData['assigned_at'] = null;
        }

        $ticket->update($ticketData);

        return redirect()->route('tickets.index')->with('success', 'Ticket actualizado correctamente.');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        return redirect()->route('tickets.index')->with('success', 'Ticket eliminado correctamente.');
    }
}
