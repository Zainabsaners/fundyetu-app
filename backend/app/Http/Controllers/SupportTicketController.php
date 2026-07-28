<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\User;
use App\Notifications\NewTicket;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    public function index()
    {
        $tickets = SupportTicket::where('user_id', auth()->id())
            ->latest()
            ->paginate(20);

        return view('tickets.index', compact('tickets'));
    }

    public function show(SupportTicket $ticket)
    {
        abort_unless((int) $ticket->user_id === (int) auth()->id(), 403);
        $ticket->load('user');

        return view('tickets.show', compact('ticket'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx|max:5120',
        ]);

        $data = [
            'user_id' => auth()->id(),
            'subject' => $validated['subject'],
            'message' => $validated['message'],
        ];

        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')->store('tickets', 'public');
        }

        $ticket = SupportTicket::create($data);

        $admins = User::role(['admin', 'super_admin'])->get();
        foreach ($admins as $admin) {
            $admin->notify(new NewTicket($ticket));
        }

        return back()->with('success', 'Ticket submitted. We will get back to you shortly.');
    }
}
