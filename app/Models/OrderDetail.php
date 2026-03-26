<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'owner_name',
        'phone_number',
        'email',
        'gender',
        'age',
        'status',
        'qr_code',
        'ticket_code',
        'tickets_id',
        'orders_id',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'tickets_id');
    }
}
