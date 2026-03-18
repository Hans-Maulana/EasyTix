<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventSchedule extends Model
{
    protected $fillable = [
        'event_id',
        'start_time',
        'end_time',
        'status',
        'event_date',
        'description',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public $incrementing = false;
    protected $keyType = 'string';

    protected static function booted()
    {
        static::creating(function ($eventSchedule) {
            $year = date('Y');
            $lastEventSchedule = EventSchedule::whereYear('created_at', $year)
                                            ->orderBy('id', 'desc')
                                            ->first();

            if ($lastEventSchedule) {
                // ambil nomor terakhir
                $lastNumber = (int) substr($lastEventSchedule->id, -3);
                $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '001';
            }

            $eventSchedule->id = "ES-{$year}-{$newNumber}";
        });
    }
}
