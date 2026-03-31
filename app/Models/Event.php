<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'name',
        'location',
        'status',
        'users_id',
        'category_id',
        'banner',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }

    public function event_schedule()
    {
        return $this->hasMany(EventSchedule::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function performers()
    {
        return $this->belongsToMany(Performer::class, 'event_performer');
    }

    public function requests()
    {
        return $this->hasMany(EventRequest::class, 'event_id', 'id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    protected static function booted()
    {
        static::creating(function ($event) {
            $year = date('Y');
            $lastEvent = Event::where('id', 'like', "EVT-{$year}-%")
                            ->orderBy('id', 'desc')
                            ->first();

            if ($lastEvent) {
                // Trim to handle CHAR columns with trailing spaces
                $lastId = trim($lastEvent->id);
                $lastNumber = (int) substr($lastId, -3);
                $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '001';
            }

            $event->id = "EVT-{$year}-{$newNumber}";
        });
    }
    public $incrementing = false;
    protected $keyType = 'string';  


}
