<?php

namespace Database\Seeders;

use App\Models\Link;
use App\Models\Tag;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class LinksTableSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $tags = Tag::all();

        $publishedLinks = [
            [
                'title' => 'Descubren nueva especie de insectos en la Amazonía',
                'url' => 'https://ejemplo.com/ciencia/nueva-especie',
                'description' => 'Científicos han descubierto una nueva especie de insectos en la selva amazónica que podría revelar información sobre la evolución.',
                'tags' => ['ciencia', 'medio ambiente'],
                'votes' => 45,
                'karma' => 60,
                'days_ago' => 2,
            ],
            [
                'title' => 'El futuro de la inteligencia artificial en 2025',
                'url' => 'https://ejemplo.com/tecnologia/ia-futuro',
                'description' => 'Expertos predicen cómo la inteligencia artificial transformará nuestras vidas en los próximos años.',
                'tags' => ['tecnología', 'ciencia'],
                'votes' => 82,
                'karma' => 90,
                'days_ago' => 1,
            ],
            [
                'title' => 'Un gato hackea una impresora y pide atún como rescate',
                'url' => 'https://ejemplo.com/tecnologia/gato-hacker',
                'description' => 'Un felino ha sido acusado de hackear una impresora doméstica y exigir atún a cambio de liberar los documentos. Aunque su abogado dice que solo caminó por el teclado.',
                'tags' => ['tecnología', 'humor'],
                'votes' => 55,
                'karma' => 62,
                'days_ago' => 2,
            ],
            [
                'title' => 'Abuelo gana torneo de eSports tras confundirse de sala',
                'url' => 'https://ejemplo.com/entretenimiento/abuelo-gamer',
                'description' => 'Entró buscando el bingo virtual y acabó ganando un torneo de Call of Duty. Ahora planea crear su canal de Twitch: “ElSniperDelIMSER”.',
                'tags' => ['videojuegos', 'humor'],
                'votes' => 88,
                'karma' => 95,
                'days_ago' => 3,
            ],
            [
                'title' => 'Encuentran una cafetera conectada a WiFi generando criptomonedas',
                'url' => 'https://ejemplo.com/tecnologia/cafetera-cripto',
                'description' => 'Lo que parecía una cafetera normal estaba minando Ethereum desde hace meses. El café salía caro, pero rendía más que una tarjeta gráfica.',
                'tags' => ['tecnología', 'economía'],
                'votes' => 63,
                'karma' => 70,
                'days_ago' => 4,
            ],
        ];

        $pendingLinks = [
            [
                'title' => 'Guía completa para aprender programación desde cero',
                'url' => 'https://ejemplo.com/educacion/aprender-programacion',
                'description' => 'Recursos gratuitos y de pago para iniciarse en el mundo de la programación sin conocimientos previos.',
                'tags' => ['educación', 'tecnología'],
                'votes' => 8,
                'karma' => 10,
                'days_ago' => 0,
            ],
            [
                'title' => 'Los beneficios del yoga para la salud mental',
                'url' => 'https://ejemplo.com/salud/yoga-mental',
                'description' => 'Estudios demuestran cómo la práctica regular de yoga puede mejorar nuestra salud mental y reducir el estrés.',
                'tags' => ['salud'],
                'votes' => 5,
                'karma' => 7,
                'days_ago' => 1,
            ],
            [
                'title' => '¿Vale la pena desconectar el microondas cuando no lo usas?',
                'url' => 'https://ejemplo.com/hogar/microondas-standby',
                'description' => 'Un extenso análisis que demuestra que al final el mayor ahorro lo logras si no compras microondas. Spoiler: también sirve para recalentar excusas.',
                'tags' => ['hogar', 'humor'],
                'votes' => 4,
                'karma' => 5,
                'days_ago' => 0,
            ],
            [
                'title' => 'Cómo sobrevivir a una reunión que pudo ser un email',
                'url' => 'https://ejemplo.com/trabajo/reuniones-evitables',
                'description' => 'Guía práctica para mantener la cordura durante esas reuniones eternas donde todos piensan lo mismo pero nadie lo dice.',
                'tags' => ['trabajo', 'humor'],
                'votes' => 6,
                'karma' => 8,
                'days_ago' => 0,
            ],
            [
                'title' => 'Ranking definitivo de las croquetas según la ciencia (y la abuela)',
                'url' => 'https://ejemplo.com/gastronomia/croquetas-ranking',
                'description' => 'Investigadores (y la abuela de uno de ellos) han probado más de 100 croquetas para elaborar este ranking. Spoiler: gana la de jamón, obvio.',
                'tags' => ['gastronomía', 'ciencia'],
                'votes' => 9,
                'karma' => 11,
                'days_ago' => 1,
            ],
        ];

        $this->createLinks($publishedLinks, $users, $tags, 'published');
        $this->createLinks($pendingLinks, $users, $tags, 'pending');

        if (config('app.repostea.allow_nsfw')) {
            $nsfwLinks = [
                [
                    'title' => 'El lado oscuro de los memes: historia de un sticker prohibido',
                    'url' => 'https://ejemplo.com/nsfw/memes-legendarios',
                    'description' => 'Un repaso a los memes más salvajes que han circulado por los grupos de WhatsApp a las 2 de la mañana. No apto para sensibles ni cuñados.',
                    'tags' => ['humor', 'nsfw'],
                    'votes' => 23,
                    'karma' => 30,
                    'days_ago' => 1,
                ],
                [
                    'title' => 'Ranking de las escenas más incómodas del cine (sí, esas)',
                    'url' => 'https://ejemplo.com/cine/escenas-nsfw',
                    'description' => 'Una recopilación de momentos del cine que te hicieron mirar a otro lado… o al menos bajar el volumen si estabas con tus padres.',
                    'tags' => ['cine', 'nsfw'],
                    'votes' => 15,
                    'karma' => 18,
                    'days_ago' => 2,
                ],
                [
                    'title' => 'Influencers que casi lo enseñan todo en directo (y lo sabían)',
                    'url' => 'https://ejemplo.com/streaming/fails-directo',
                    'description' => 'Desde pantalones olvidados hasta cámaras encendidas por error: estos clips casi acaban con carreras... o las dispararon.',
                    'tags' => ['entretenimiento', 'nsfw'],
                    'votes' => 40,
                    'karma' => 45,
                    'days_ago' => 3,
                ],
            ];
            $this->createLinks($nsfwLinks, $users, $tags, 'published');
        }
    }

    private function createLinks($linksData, $users, $tags, $status)
    {
        foreach ($linksData as $linkData) {

            $user = $users->random();

            $link = new Link;
            $link->title = $linkData['title'];
            $link->url = $linkData['url'];
            $link->content = $linkData['content'] ?? null;
            $link->description = $linkData['description'];
            $link->status = $status;
            $link->votes = $linkData['votes'];
            $link->karma = $linkData['karma'];
            $link->user_id = $user->id;
            $link->created_at = Carbon::now()->subDays($linkData['days_ago']);

            if ($status === 'published') {
                $link->promoted_at = Carbon::now()->subDays($linkData['days_ago'])->addHours(6);
            }

            $link->save();

            foreach ($linkData['tags'] as $tagName) {
                $tag = $tags->where('name', $tagName)->first();
                if ($tag) {
                    $link->tags()->attach($tag->id);
                }
            }
        }
    }
}
