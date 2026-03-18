<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genres = [
            ['name' => 'Rock'],
            ['name' => 'Pop'],
            ['name' => 'Hip-Hop'],
            ['name' => 'R&B'],
            ['name' => 'Jazz'],
            ['name' => 'Blues'],
            ['name' => 'Country'],
            ['name' => 'Classical'],
            ['name' => 'Opera'],
            ['name' => 'Electronic Dance Music (EDM)'],
            ['name' => 'House'],
            ['name' => 'Techno'],
            ['name' => 'Trance'],
            ['name' => 'Reggae'],
            ['name' => 'Ska'],
            ['name' => 'Metal'],
            ['name' => 'Punk'],
            ['name' => 'Indie'],
            ['name' => 'Alternative Rock'],
            ['name' => 'Folk'],
            ['name' => 'Soul'],
            ['name' => 'Gospel'],
            ['name' => 'Afrobeat'],
            ['name' => 'K-Pop'],
            ['name' => 'J-Pop'],
            ['name' => 'Latin'],
            ['name' => 'Salsa'],
            ['name' => 'Merengue'],
            ['name' => 'Flamenco'],
            ['name' => 'Bollywood'],
            ['name' => 'World Music'],
            ['name' => 'Traditional African'],
            ['name' => 'Arabic Music'],
            ['name' => 'Chinese Traditional'],
            ['name' => 'Indian Classical'],
            ['name' => 'Korean Traditional'],
        ];

        \Illuminate\Support\Facades\DB::table('genres')->insert($genres);
    }
}
