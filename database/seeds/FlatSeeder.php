<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FlatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::findOrFail(5)->flatsOwned()->create([
            'label' => 'Alcoloc',
            'avatar' => '*',
            'key' => Str::random(8),
        ])->participants()->attach([1, 4, 5]);
        usleep(1000000);

        User::findOrFail(1)->flatsOwned()->create([
            'label' => 'Emma, Marie et Sarah',
            'avatar' => '*',
            'key' => 'jury2020',
        ])->participants()->attach([1, 2, 3]);
    }
}
