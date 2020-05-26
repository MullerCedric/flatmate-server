<?php

use App\User;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::findOrFail(1)->categories()->create([
            'label' => 'Soirées',
            'color' => '#CC85FF',
            'weight' => 4,
            'type' => 'events',
        ]);
        User::findOrFail(1)->categories()->create([
            'label' => 'École',
            'color' => '#007377',
            'weight' => 3,
            'type' => 'events',
        ]);
        User::findOrFail(1)->categories()->create([
            'label' => 'Ménage',
            'color' => '#FFD378',
            'weight' => 1,
            'type' => 'events',
        ]);
        User::findOrFail(1)->categories()->create([
            'label' => 'Transactions',
            'color' => '#228539',
            'weight' => 2,
            'type' => 'events',
        ]);
        User::findOrFail(1)->categories()->create([
            'label' => 'Deadline',
            'color' => '#E8796D',
            'weight' => 0,
            'type' => 'events',
        ]);
    }
}
