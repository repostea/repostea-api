<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{
    public function run()
    {
        $tags = [
            'tecnología',
            'ciencia',
            'política',
            'deportes',
            'entretenimiento',
            'música',
            'cine',
            'libros',
            'gastronomía',
            'viajes',
            'salud',
            'educación',
            'economía',
            'arte',
            'medio ambiente',
            'humor',
            'nsfw',
            'hogar',
            'trabajo',
            'videojuegos',
        ];

        foreach ($tags as $tagName) {
            Tag::create(['name' => $tagName]);
        }
    }
}
