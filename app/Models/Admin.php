<?php

namespace App\Models;

class Admin extends User
{
    // tetap pakai tabel users
    protected $table = 'users';

    // kalau mau filter default hanya admin
    protected static function booted()
    {
        static::addGlobalScope('admin', function ($query) {
            $query->where('role', 'admin');
        });
    }
}

