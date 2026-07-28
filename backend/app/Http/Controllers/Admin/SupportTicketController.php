<?php

namespace App\Http\Controllers\Admin;

use App\Models\SupportTicket;
use App\Notifications\TicketReplied;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SupportTicketController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin|super_admin']);
    }

    public function index()
    {
        $tickets = SupportTicket::with('user')->latest()->get();
        return view('admin.tickets.index', compact('tickets'));
    }

    public function show(SupportTicket $ticket)
    {
        $ticket->load('user');
        return view('admin.tickets.show', compact('ticket'));
    }

    public function reply(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate([
            'reply' => 'required|string',
        ]);

        $ticket->update([
            'reply' => $validated['reply'],
            'replied_at' => now(),
            'status' => 'closed',
        ]);

        $ticket->user->notify(new TicketReplied($ticket));

        return redirect()->route('admin.tickets.index')->with('success', 'Reply sent and ticket closed.');
    }

    public function reopen(SupportTicket $ticket)
    {
        $ticket->update(['status' => 'open']);
        return back()->with('success', 'Ticket reopened.');
    }
}
