<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    protected $fillable = ['name'];

    public function performers()
    {
        return $this->belongsToMany(Performer::class, 'performer_genre');
    }
}
