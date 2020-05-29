<?php

use App\Discussion;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Discussion::all()->each(function ($discussion) {
            $participants = $discussion->participants;
            $from = $participants->random(1)->first();
            $participantsId = $participants->pluck('id')->all();
            $message = $discussion->messages()->create([
                'type' => 'discussion',
                'content' => $from->name . ' a créé la discussion',
            ]);

            $message->readBy()->syncWithoutDetaching($participantsId);

            for ($i = 1; $i <= rand(5, 250); $i++) {
                $fromId = $discussion->participants->random(1)->first()->id;
                $message = $discussion->messages()->save(factory(App\Message::class)->make([
                    'from_id' => $fromId,
                ]));
                $message->readBy()->attach($participantsId);
            }

        });
    }
}
