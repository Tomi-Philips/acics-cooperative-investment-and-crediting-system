<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class SupportTicketController extends Controller
{
    /**
     * Display open support tickets.
     *
     * @return \Illuminate\View\View
     */
    public function openTickets()
    {
        try {
            $tickets = SupportTicket::where('status', 'open')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } catch (\Exception $e) {
            // If there's an error (like table doesn't exist), return empty paginated collection
            $tickets = new LengthAwarePaginator(
                collect([]), // Empty collection
                0, // Total items
                10, // Items per page
                request()->input('page', 1), // Current page
                [
                    'path' => request()->url(),
                    'pageName' => 'page',
                ]
            );
            $tickets->withPath(request()->url());
        }

        $categories = SupportTicket::getCategories();
        return view('admin.support_ticket.open_ticket', compact('tickets', 'categories'));
    }

    /**
     * Display closed support tickets.
     *
     * @return \Illuminate\View\View
     */
    public function closedTickets()
    {
        try {
            $tickets = SupportTicket::where('status', 'closed')
                ->orderBy('updated_at', 'desc')
                ->paginate(10);
        } catch (\Exception $e) {
            // If there's an error (like table doesn't exist), return empty paginated collection
            $tickets = new LengthAwarePaginator(
                collect([]), // Empty collection
                0, // Total items
                10, // Items per page
                request()->input('page', 1), // Current page
                [
                    'path' => request()->url(),
                    'pageName' => 'page',
                ]
            );
            $tickets->withPath(request()->url());
        }

        $categories = SupportTicket::getCategories();
        return view('admin.support_ticket.closed_ticket', compact('tickets', 'categories'));
    }

    /**
     * Show the reply form for a specific ticket.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function showReply($id)
    {
        $ticket = SupportTicket::with(['user', 'replies.user'])
            ->findOrFail($id);

        return view('admin.support_ticket.reply_ticket', compact('ticket'));
    }

    /**
     * Close a support ticket.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function closeTicket($id)
    {
        $ticket = SupportTicket::findOrFail($id);
        
        $ticket->update([
            'status' => 'closed',
            'closed_by' => Auth::id(),
            'closed_at' => now()
        ]);

        return redirect()->route('admin.tickets.open')
            ->with('success', 'Ticket #' . $ticket->id . ' has been closed successfully.');
    }

    /**
     * Reopen a closed support ticket.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reopenTicket($id)
    {
        $ticket = SupportTicket::findOrFail($id);
        
        $ticket->update([
            'status' => 'open',
            'closed_by' => null,
            'closed_at' => null
        ]);

        return redirect()->route('admin.tickets.closed')
            ->with('success', 'Ticket #' . $ticket->id . ' has been reopened successfully.');
    }

    /**
     * Store a reply to a support ticket.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeReply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $ticket = SupportTicket::findOrFail($id);

        // Create the reply
        $ticket->replies()->create([
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_admin' => true,
        ]);

        // Handle close ticket request
        if ($request->has('close_ticket') && $request->close_ticket == '1') {
            $ticket->update([
                'status' => 'closed',
                'closed_by' => Auth::id(),
                'closed_at' => now()
            ]);

            return redirect()->route('admin.tickets.open')
                ->with('success', 'Reply sent and ticket closed successfully.');
        }

        // Update ticket status if it was closed (reopen for regular reply)
        if ($ticket->status === 'closed') {
            $ticket->update(['status' => 'open']);
        }

        return redirect()->route('admin.tickets.show_reply', $id)
            ->with('success', 'Reply sent successfully.');
    }
}
