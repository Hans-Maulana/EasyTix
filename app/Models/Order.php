<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id', 'users_id', 'events_id', 'total_amount'];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'events_id');
    }
}

