<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\NotificationController;
use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SupportController extends Controller
{
    /**
     * Display the support page with user's tickets.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();

        // Get status filter
        $status = request('status');

        // Query tickets
        $ticketsQuery = SupportTicket::where('user_id', $user->id);

        // Apply status filter if provided
        if ($status) {
            $ticketsQuery->where('status', $status);
        }

        // Get paginated tickets
        $tickets = $ticketsQuery->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.support', [
            'user' => $user,
            'tickets' => $tickets
        ]);
    }

    /**
     * Display the ticket details.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $user = Auth::user();
        $ticket = SupportTicket::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Get ticket replies
        $replies = $ticket->replies()
            ->orderBy('created_at')
            ->get();

        return view('user.ticket_details', [
            'user' => $user,
            'ticket' => $ticket,
            'replies' => $replies
        ]);
    }

    /**
     * Create a new support ticket.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Validate the request
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'category' => 'required|string|in:general,account,loan,savings,shares,commodity',
            'message' => 'required|string|max:5000',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        // Generate a ticket number
        $ticketNumber = SupportTicket::generateTicketNumber();

        // Handle file upload
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('ticket_attachments', 'public');
        }

        // Create the ticket
        $ticket = SupportTicket::create([
            'user_id' => $user->id,
            'ticket_number' => $ticketNumber,
            'subject' => $validated['subject'],
            'category' => $validated['category'],
            'message' => $validated['message'],
            'attachment' => $attachmentPath,
            'status' => 'open',
        ]);

        // Create notification for admin users
        $this->createAdminNotification($ticket);

        return redirect()->route('user.support')
            ->with('success', 'Your support ticket has been submitted successfully. Ticket number: ' . $ticketNumber);
    }

    /**
     * Add a reply to a ticket.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reply(Request $request, $id)
    {
        $user = Auth::user();
        $ticket = SupportTicket::where('id', $id)
            ->where('user_id', $user->id)
            ->where('status', 'open')
            ->firstOrFail();

        // Validate the request
        $validated = $request->validate([
            'message' => 'required|string|max:5000',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        // Handle file upload
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('ticket_replies', 'public');
        }

        // Create the ticket reply
        SupportTicketReply::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'message' => $validated['message'],
            'attachment' => $attachmentPath,
            'is_admin' => false,
        ]);

        // Update the ticket
        $ticket->update([
            'updated_at' => now(),
        ]);

        return redirect()->route('user.support.show', $ticket->id)
            ->with('success', 'Your reply has been submitted successfully.');
    }

    /**
     * Close a ticket.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function close($id)
    {
        $user = Auth::user();
        $ticket = SupportTicket::where('id', $id)
            ->where('user_id', $user->id)
            ->where('status', 'open')
            ->firstOrFail();

        // Update the ticket status
        $ticket->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);

        return redirect()->route('user.support')
            ->with('success', 'Ticket #' . $ticket->ticket_number . ' has been closed successfully.');
    }

    /**
     * Create notification for admin users when a new ticket is submitted.
     *
     * @param  \App\Models\SupportTicket  $ticket
     * @return void
     */
    private function createAdminNotification($ticket)
    {
        // Get all admin users
        $adminUsers = User::where('role', 'admin')->get();

        foreach ($adminUsers as $admin) {
            // Create notification for each admin
            NotificationController::createNotification(
                $admin->id,
                'New Support Ticket',
                'A new support ticket has been submitted: ' . $ticket->subject,
                'support_ticket',
                route('admin.tickets.show_reply', $ticket->id),
                [
                    'ticket_id' => $ticket->id,
                    'ticket_number' => $ticket->ticket_number,
                    'user_name' => $ticket->user->name,
                ]
            );
        }
    }
}
