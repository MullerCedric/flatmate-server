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
        $event = Event::create([
            'label' => 'Récurer la baignoire',
            'flat_id' => Flat::findOrFail(2)->id,
            'confirm' => 'during',
            'start_date' => '2020-04-14 09:01:15',
            'interval' => 1814400000,
        ]);
        $event->participants()->attach([1, 2, 3]);
        $event->categories()->attach(Category::whereLabel('Ménage')->firstOrFail()->id);

        $event = Event::create([
            'label' => 'Sortir les poubelles',
            'flat_id' => Flat::findOrFail(2)->id,
            'confirm' => 'during',
            'start_date' => '2020-04-13 18:30:00',
            'interval' => 604800000,
        ]);
        $event->participants()->attach([1]);
        $event->categories()->attach(Category::whereLabel('Ménage')->firstOrFail()->id);

        $event = Event::create([
            'label' => 'Soirée Hunger Games',
            'flat_id' => Flat::findOrFail(2)->id,
            'confirm' => 'during',
            'start_date' => '2020-04-26 13:00:00',
            'end_date' => '2020-05-10 15:00:00',
            'interval' => 604800000,
        ]);
        $event->participants()->attach([1, 2, 3]);
        $event->categories()->attach(Category::whereLabel('Soirées')->firstOrFail()->id);

        $event = Event::create([
            'label' => 'Laver la cuisine',
            'flat_id' => Flat::findOrFail(2)->id,
            'confirm' => 'during',
            'start_date' => '2020-05-21 14:00:00',
            'interval' => 1209600000,
        ]);
        $event->participants()->attach([1]);
        $event->categories()->attach(Category::whereLabel('Ménage')->firstOrFail()->id);

        $event = Event::create([
            'label' => 'Soirée pizza entre nous',
            'flat_id' => Flat::findOrFail(2)->id,
            'confirm' => 'before',
            'start_date' => '2020-05-21 18:30:00',
        ]);
        $event->participants()->attach([1, 2, 3]);
        $event->categories()->attach(Category::whereLabel('Soirées')->firstOrFail()->id);

        $event = Event::create([
            'label' => 'Payer loyer',
            'flat_id' => Flat::findOrFail(2)->id,
            'confirm' => 'before',
            'start_date' => '2020-04-05 07:00:00',
            'interval' => 2592000000,
        ]);
        $event->participants()->attach([1]);
        $event->categories()->attach(Category::whereLabel('Transactions')->firstOrFail()->id);

        $event = Event::create([
            'label' => 'Payer les charges',
            'flat_id' => Flat::findOrFail(2)->id,
            'confirm' => 'before',
            'start_date' => '2020-04-05 07:00:00',
            'interval' => 2592000000,
        ]);
        $event->participants()->attach([1]);
        $event->categories()->attach(Category::whereLabel('Transactions')->firstOrFail()->id);

        $event = Event::create([
            'label' => 'Réussir le jury',
            'start_date' => '2020-06-18 06:30:00',
        ]);
        $event->participants()->attach([1]);
        $event->categories()->attach(Category::whereLabel('Travail')->firstOrFail()->id);

        $event = Event::create([
            'label' => 'Profiter des vacances',
            'start_date' => '2020-06-30 22:00:00',
            'end_date' => '2020-07-31 21:59:59',
            'interval' => 86400000,
        ]);
        $event->participants()->attach([1]);
    }
}
