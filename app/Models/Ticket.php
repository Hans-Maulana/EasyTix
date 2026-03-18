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
            $lastTicket = Ticket::whereYear('created_at', $year)
                            ->orderBy('id', 'desc')
                            ->first();

            if ($lastTicket) {
                // ambil nomor terakhir
                $lastNumber = (int) substr($lastTicket->id, -3);
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
