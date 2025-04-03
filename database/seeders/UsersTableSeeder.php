<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends TenantBaseSeeder
{
    protected function runSeed(): void
    {
        User::create([
            'username' => 'admin',
            'email' => 'admin@repostea.local',
            'password' => Hash::make('password'),
            'karma' => 20.0,
            'level' => 10,
            'admin' => true,
            'moderator' => true,
            'verified' => true,
            'bio' => 'Admin supremo del sitio. Dice que solo entra para revisar cosas, pero sabemos que viene por los comentarios.',
            'email_verified_at' => now(),
            'tenant_id' => $this->tenantId,
        ]);

        User::create([
            'username' => 'mod_julia',
            'email' => 'moderator@repostea.local',
            'password' => Hash::make('password'),
            'karma' => 15.0,
            'level' => 5,
            'admin' => false,
            'moderator' => true,
            'verified' => true,
            'bio' => 'Moderadora profesional y cazadora de trolls. Si desaparece un comentario, probablemente fue ella.',
            'email_verified_at' => now(),
            'tenant_id' => $this->tenantId,
        ]);

        $users = [
            [
                'username' => 'techoverlord',
                'email' => 'techoverlord@repostea.local',
                'karma' => 12.0,
                'bio' => 'Apasionado de la tecnología, aunque aún no sabe programar un reloj digital sin el manual.',
            ],
            [
                'username' => 'maria_gamer',
                'email' => 'maria_gamer@repostea.local',
                'karma' => 8.0,
                'bio' => 'Amante de la música, los videojuegos y los memes que ya no dan gracia pero igual comparte.',
            ],
            [
                'username' => 'ideaslocas',
                'email' => 'ideaslocas@repostea.local',
                'karma' => 6.0,
                'bio' => 'Explorador de nuevas ideas, conspiraciones interesantes y teorías que ni Google se atreve a indexar.',
            ],
            [
                'username' => 'lector_oculto',
                'email' => 'lector@repostea.local',
                'karma' => 5.0,
                'bio' => 'Lector ávido, escritor ocasional y corrector ortográfico no oficial de los comentarios.',
            ],
            [
                'username' => 'chefdeverdad',
                'email' => 'cocina@repostea.local',
                'karma' => 4.0,
                'bio' => 'Entusiasta de la cocina. Su última creación fue un arroz que se pasó de arte a ladrillo en 5 minutos.',
            ],
            [
                'username' => 'viajeywifi',
                'email' => 'viajes@repostea.local',
                'karma' => 9.0,
                'bio' => 'Ha viajado a 22 países y en ninguno encontró buen WiFi. Comparte mapas, anécdotas y contraseñas de cafeterías.',
            ],
            [
                'username' => 'memelord2024',
                'email' => 'memes@repostea.local',
                'karma' => 7.5,
                'bio' => 'Publica 10 memes al día, aunque solo uno triunfa. Cree que el humor salvará internet (y probablemente tiene razón).',
            ],
            [
                'username' => 'anon_dev',
                'email' => 'dev@repostea.local',
                'karma' => 11.0,
                'bio' => 'Programador misterioso que aparece en los hilos técnicos, responde en monosílabos y desaparece como Batman.',
            ],
        ];

        foreach ($users as $userData) {
            User::create(array_merge([
                'password' => Hash::make('password'),
                'level' => 0,
                'admin' => false,
                'moderator' => false,
                'verified' => true,
                'email_verified_at' => now(),
                'tenant_id' => $this->tenantId,
            ], $userData));
        }
    }
}
