<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organizer extends User
{
    // tetap pakai tabel users
    protected $table = 'users';

    // kalau mau filter default hanya admin
    protected static function booted()
    {
        static::addGlobalScope('organizer', function ($query) {
            $query->where('role', 'organizer');
        });
    }
}