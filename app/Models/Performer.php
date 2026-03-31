<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Performer extends Model
{
    protected $fillable = ['name', 'image'];

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'performer_genre');
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_performer');
    }
}
