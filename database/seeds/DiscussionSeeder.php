<?php

use App\Discussion;
use App\Flat;
use Illuminate\Database\Seeder;

class DiscussionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Discussion::create([
            'flat_id' => Flat::findOrFail(1)->id,
        ])->participants()->attach([1, 2, 3]);

        Discussion::create([
            'label' => 'Liste des courses',
            'flat_id' => Flat::findOrFail(1)->id,
        ])->participants()->attach([1, 2, 3]);

        Discussion::create([
            'flat_id' => Flat::findOrFail(1)->id,
        ])->participants()->attach([1, 2]);
    }
}
