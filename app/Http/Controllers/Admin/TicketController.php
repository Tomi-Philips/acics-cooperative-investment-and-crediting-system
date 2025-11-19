<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\NotificationController;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Display a listing of open tickets.
     *
     * @return \Illuminate\View\View
     */
    public function openTickets()
    {
        $tickets = SupportTicket::where('status', 'open')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.support_ticket.open_ticket', compact('tickets'));
    }

    /**
     * Display a listing of closed tickets.
     *
     * @return \Illuminate\View\View
     */
    public function closedTickets()
    {
        $tickets = SupportTicket::where('status', 'closed')->orderBy('created_at', 'desc')->paginate(10);
        $openTicketsCount = SupportTicket::where('status', 'open')->count();
        return view('admin.support_ticket.closed_ticket', compact('tickets', 'openTicketsCount'));
    }

    /**
     * Display the specified ticket for reply.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function showForReply($id)
    {
        $ticket = SupportTicket::with('replies')->findOrFail($id);
        return view('admin.support_ticket.reply_ticket', compact('ticket'));
    }

    /**
     * Store a reply to a ticket.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeReply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $ticket = SupportTicket::findOrFail($id);

        $reply = SupportTicketReply::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_admin' => true,
        ]);

        // Create notification for the ticket owner
        $this->createUserNotification($ticket, $reply);

        // Update ticket status if needed
        if ($request->has('close_ticket') && $request->close_ticket) {
            $ticket->update(['status' => 'closed', 'closed_at' => now()]);
            return redirect()->route('admin.tickets.closed')->with('success', 'Reply sent and ticket closed.');
        }

        // Update the ticket's updated_at timestamp
        $ticket->touch();

        return redirect()->route('admin.tickets.open')->with('success', 'Reply sent successfully.');
    }

    /**
     * Create notification for the user when an admin replies to their ticket.
     *
     * @param  \App\Models\SupportTicket  $ticket
     * @param  \App\Models\SupportTicketReply  $reply
     * @return void
     */
    private function createUserNotification($ticket, $reply)
    {
        // Create notification for the ticket owner
        NotificationController::createNotification(
            $ticket->user_id,
            'Support Ticket Reply',
            'An administrator has replied to your support ticket: ' . $ticket->subject,
            'support_ticket',
            route('user.support.show', $ticket->id),
            [
                'ticket_id' => $ticket->id,
                'ticket_number' => $ticket->ticket_number,
                'reply_id' => $reply->id,
            ]
        );
    }

    /**
     * Close a ticket.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function closeTicket($id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $ticket->update(['status' => 'closed', 'closed_at' => now()]);

        return redirect()->route('admin.tickets.closed')->with('success', 'Ticket closed successfully.');
    }

    /**
     * Reopen a closed ticket.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reopenTicket($id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $ticket->update(['status' => 'open', 'closed_at' => null]);

        return redirect()->route('admin.tickets.open')->with('success', 'Ticket reopened successfully.');
    }
}
