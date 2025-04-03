<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Link;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CommentsTableSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $links = Link::where('status', 'published')->get();

        $maxCommentsPerLink = config('app.repostea.max_comments_per_link', 15);
        $maxRepliesPerComment = config('app.repostea.max_replies_per_comment', 5);
        $initialKarma = config('app.repostea.initial_karma', 6.0);

        foreach ($links as $link) {
            $commentCount = rand(0, $maxCommentsPerLink);

            $parentComments = [];

            for ($i = 0; $i < $commentCount; $i++) {
                $user = $users->random();

                $comment = new Comment;
                $comment->user_id = $user->id;
                $comment->link_id = $link->id;
                $comment->content = $this->getRandomComment();
                $comment->votes = rand(-5, 20);
                $comment->karma = $comment->votes * ($user->karma / $initialKarma);
                $comment->created_at = Carbon::now()->subDays(rand(0, 3))->subHours(rand(0, 23));
                $comment->save();

                $parentComments[] = $comment;
            }

            foreach ($parentComments as $parentComment) {
                if (rand(0, 1)) {
                    $replyCount = rand(1, $maxRepliesPerComment);

                    for ($i = 0; $i < $replyCount; $i++) {
                        $user = $users->random();

                        $reply = new Comment;
                        $reply->user_id = $user->id;
                        $reply->link_id = $link->id;
                        $reply->parent_id = $parentComment->id;
                        $reply->content = $this->getRandomReply();
                        $reply->votes = rand(-2, 10);
                        $reply->karma = $reply->votes * ($user->karma / $initialKarma);
                        $reply->created_at = Carbon::parse($parentComment->created_at)->addHours(rand(1, 5));
                        $reply->save();
                    }
                }
            }
        }
    }

    private function getRandomComment()
    {
        $comments = [
            'Excelente artículo, muy informativo.',
            'No estoy de acuerdo con algunos puntos, pero está bien argumentado.',
            'Gracias por compartir esta información, no lo sabía.',
            'Interesante perspectiva sobre el tema.',
            'Me gustaría ver más contenido como este.',
            'Creo que falta profundizar en algunos aspectos importantes.',
            'Totalmente de acuerdo con el autor.',
            '¿Alguien tiene más información sobre esto?',
            'Ya habían publicado algo similar hace tiempo.',
            'El tema es fascinante, pero el artículo podría mejorar.',
            'No entiendo cómo esto es relevante.',
            'Sin duda uno de los mejores artículos que he leído recientemente.',
            'Esto explica muchas cosas que no comprendía antes.',
            '¿Hay alguna fuente que respalde estas afirmaciones?',
            'Me parece un tema muy sobrevalorado.',
            'Después de leer esto... sigo sin entender nada 😅',
            'Pensé que esto era una receta, pero aprendí algo de política.',
            'He leído mejores hilos en Twitter, pero bien.',
            'He llegado aquí por error y me quedé leyendo todo.',
            'Amo cómo se pelean en los comentarios mientras yo solo vine por los memes.',
            'Lo leí dos veces y aún no sé si estoy más informado o más confundido. Pero bueno, aprendí una palabra nueva, así que eso cuenta.',
            'Este artículo me dejó pensando... aunque no sé si eso es bueno o malo. Voy a consultarlo con mi almohada.',
            'Solo entré para ver los comentarios, y honestamente, no me decepcionaron. Aquí hay más drama que en una telenovela venezolana.',
            'Me ha gustado tanto que hasta pensé en imprimirlo y colgarlo en la nevera. Pero luego recordé que no tengo impresora.',
            'Esto debería enseñarse en los institutos, justo después de la clase de cómo sobrevivir sin batería.',
        ];

        return $comments[array_rand($comments)];
    }

    private function getRandomReply()
    {
        $replies = [
            'Estoy completamente de acuerdo contigo.',
            'No creo que hayas entendido el punto principal.',
            'Tienes razón, no lo había visto desde esa perspectiva.',
            '¿Podrías elaborar más sobre lo que dices?',
            'Gracias por tu comentario, me ha hecho reflexionar.',
            'Discrepo totalmente con tu opinión.',
            'Interesante punto de vista.',
            'Ya se había discutido esto en otro hilo.',
            'Me has convencido con tus argumentos.',
            'Creo que estás confundiendo los conceptos.',
            'Eso que dices suena bien... pero ¿y si no?',
            'Te leí en voz alta y ahora el perro está confundido.',
            'Comentario patrocinado por tu cuñado en Nochebuena.',
            'Esto lo vi en un TikTok, así que debe ser cierto.',
            'Pensaba responderte, pero me dio hambre.',
            'Estaba a punto de estar en desacuerdo contigo, pero luego me di cuenta de que ni siquiera sé de qué estamos hablando. Aún así, buen comentario.',
            'Me gusta tu entusiasmo, pero creo que necesitas una pausa. Tómate un café, respira hondo y luego seguimos con el debate.',
            'Tú comentario me recordó a esa vez que intenté arreglar la lavadora viendo un tutorial en YouTube. Mucho entusiasmo, pero acabó saliendo agua por la ventana.',
            'Tu lógica es tan perfecta que me hace sospechar que eres una IA encubierta. Pero de las buenas, eh.',
            'Leí tu respuesta tres veces y aún no sé si estoy de acuerdo, pero al menos me entretuve. Gracias por eso.',
        ];

        return $replies[array_rand($replies)];
    }
}
