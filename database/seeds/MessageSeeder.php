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
            for ($i = 1; $i <= rand(0, 20); $i++) {
                $fromId = $discussion->participants->random(1)->first()->id;
                $discussion->messages()->save(factory(App\Message::class)->make([
                    'from_id' => $fromId,
                ]));
            }

        });
    }
}
