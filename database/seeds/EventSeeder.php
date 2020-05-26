<?php

use App\Category;
use App\Event;
use App\Flat;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Event::create([
            'label' => 'Récurer la baignoire',
            'flat_id' => Flat::findOrFail(1)->id,
            'category_id' => Category::whereLabel('Ménage')->firstOrFail()->id,
            'confirm' => 'during',
            'start_date' => '2020-04-14 09:01:15',
            'interval' => 1814400000,
        ])->participants()->attach([1, 2, 3]);

        Event::create([
            'label' => 'Sortir les poubelles',
            'flat_id' => Flat::findOrFail(1)->id,
            'category_id' => Category::whereLabel('Ménage')->firstOrFail()->id,
            'confirm' => 'during',
            'start_date' => '2020-04-13 18:30:00',
            'interval' => 604800000,
        ])->participants()->attach([1]);

        Event::create([
            'label' => 'Soirée Hunger Games',
            'flat_id' => Flat::findOrFail(1)->id,
            'category_id' => Category::whereLabel('Soirées')->firstOrFail()->id,
            'confirm' => 'during',
            'start_date' => '2020-04-26 13:00:00',
            'end_date' => '2020-05-10 15:00:00',
            'interval' => 604800000,
        ])->participants()->attach([1, 2, 3]);

        Event::create([
            'label' => 'Laver la cuisine',
            'flat_id' => Flat::findOrFail(1)->id,
            'category_id' => Category::whereLabel('Ménage')->firstOrFail()->id,
            'confirm' => 'during',
            'start_date' => '2020-05-21 14:00:00',
            'interval' => 1209600000,
        ])->participants()->attach([1]);

        Event::create([
            'label' => 'Soirée pizza entre nous',
            'flat_id' => Flat::findOrFail(1)->id,
            'category_id' => Category::whereLabel('Soirées')->firstOrFail()->id,
            'confirm' => 'before',
            'start_date' => '2020-05-21 18:30:00',
        ])->participants()->attach([1, 2, 3]);

        Event::create([
            'label' => 'Payer loyer',
            'flat_id' => Flat::findOrFail(1)->id,
            'category_id' => Category::whereLabel('Transactions')->firstOrFail()->id,
            'confirm' => 'before',
            'start_date' => '2020-04-05 07:00:00',
            'interval' => 2592000000,
        ])->participants()->attach([1]);

        Event::create([
            'label' => 'Payer les charges',
            'flat_id' => Flat::findOrFail(1)->id,
            'category_id' => Category::whereLabel('Transactions')->firstOrFail()->id,
            'confirm' => 'before',
            'start_date' => '2020-04-05 07:00:00',
            'interval' => 2592000000,
        ])->participants()->attach([1]);

        Event::create([
            'label' => 'Réussir le jury',
            'category_id' => Category::whereLabel('École')->firstOrFail()->id,
            'start_date' => '2020-06-18 06:30:00',
        ])->participants()->attach([1]);

        Event::create([
            'label' => 'Profiter des vacances',
            'start_date' => '2020-06-30 22:00:00',
            'end_date' => '2020-07-31 21:59:59',
            'interval' => 86400000,
        ])->participants()->attach([1]);
    }
}
