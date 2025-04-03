<?php

namespace Database\Seeders;

use App\Models\Link;
use App\Models\User;
use App\Models\Vote;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class VotesTableSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $links = Link::all();

        foreach ($links as $link) {

            $author = User::find($link->user_id);

            Vote::create([
                'user_id' => $author->id,
                'link_id' => $link->id,
                'value' => 1,
                'karma_value' => $author->karma / 6,
                'created_at' => $link->created_at,
            ]);

            $otherUsers = $users->where('id', '!=', $author->id);

            $remainingVotes = $link->votes - 1;
            $votersCount = min($remainingVotes, $otherUsers->count());

            $voters = $otherUsers->random($votersCount);

            foreach ($voters as $voter) {

                $value = rand(1, 10) <= 8 ? 1 : -1;

                Vote::create([
                    'user_id' => $voter->id,
                    'link_id' => $link->id,
                    'value' => $value,
                    'karma_value' => $value * ($voter->karma / 6),
                    'created_at' => Carbon::parse($link->created_at)->addMinutes(rand(5, 1440)),
                ]);
            }
        }
    }
}
