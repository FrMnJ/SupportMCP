<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketControllerAPI extends Controller
{
    public function show(int $id)
    {
        $ticket = Ticket::with(['client', 'assignedTo', 'system'])->find($id);
        if (!$ticket) {
            return response()->json(['message' => 'Ticket not found'], 404);
        }
        return response()->json($ticket->toDto(), 200);
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
            'score' => 'integer',
        ]);

        $ticket = Ticket::create($request->all());

        return response()->json(['ticket' => $ticket->toDto() ], 200);
    }

}
