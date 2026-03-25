<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'id',
        'capacity',
        'price',
        'ticket_types_id',
        'event_schedules_id',
    ];

    public function ticket_type()
    {
        return $this->belongsTo(TicketType::class, 'ticket_types_id');
    }

    public function event_schedule()
    {
        return $this->belongsTo(EventSchedule::class, 'event_schedules_id');
    }

    protected static function booted()
    {
        static::creating(function ($ticket) {
            $year = date('Y');
            $lastTicket = Ticket::where('id', 'like', "TKT-{$year}-%")
                            ->orderBy('id', 'desc')
                            ->first();

            if ($lastTicket) {
                // Trim to handle CHAR columns with trailing spaces
                $lastId = trim($lastTicket->id);
                $lastNumber = (int) substr($lastId, -3);
                $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '001';
            }

            $ticket->id = "TKT-{$year}-{$newNumber}";
        });
    }

    public $incrementing = false;
    protected $keyType = 'string'; 
}
