<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'support_tickets';

    protected $fillable = [
        'user_id',
        'ticket_number',
        'subject',
        'category',
        'message',
        'attachment',
        'status',
        'closed_by',
        'closed_at',
    ];

    protected $casts = [
        'closed_at' => 'datetime',
    ];

    /**
     * Get the user that owns the ticket.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the replies for the ticket.
     */
    public function replies()
    {
        return $this->hasMany(SupportTicketReply::class)->orderBy('created_at', 'asc');
    }

    /**
     * Generate a unique ticket number.
     */
    public static function generateTicketNumber()
    {
        $prefix = 'TKT-';
        $random = mt_rand(1000, 9999);
        $ticketNumber = $prefix . $random;

        // Check if the ticket number already exists
        while (self::where('ticket_number', $ticketNumber)->exists()) {
            $random = mt_rand(1000, 9999);
            $ticketNumber = $prefix . $random;
        }

        return $ticketNumber;
    }

    /**
     * Get available ticket categories.
     *
     * @return array
     */
    public static function getCategories()
    {
        return [
            'general' => 'General Inquiry',
            'account' => 'Account Issue',
            'loan' => 'Loan Related',
            'savings' => 'Savings Related',
            'shares' => 'Shares Related',
            'commodity' => 'Commodity Related',
        ];
    }

    /**
     * Get the formatted category name.
     *
     * @return string
     */
    public function getCategoryNameAttribute()
    {
        $categories = self::getCategories();
        return $categories[$this->category] ?? ucfirst($this->category);
    }
}
