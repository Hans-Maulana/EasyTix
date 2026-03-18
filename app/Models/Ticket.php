<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'capacity',
        'price',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    protected static function booted()
    {
        static::creating(function ($ticket) {
            $year = date('Y');
            $lastTicket = Event::whereYear('created_at', $year)
                            ->orderBy('id', 'desc')
                            ->first();

            if ($lastTicket) {
                // ambil nomor terakhir
                $lastNumber = (int) substr($lastTicket->id, -3);
                $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '001';
            }

            $ticket->id = "-{$year}-{$newNumber}";
        });
    }
    public $incrementing = false;
    protected $keyType = 'string'; 
}
