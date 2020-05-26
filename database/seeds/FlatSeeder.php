<?php

use Illuminate\Database\Seeder;
use App\User;

class FlatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::findOrFail(1)->flatsOwned()->create([
            'label' => 'Emma, Marie et Sarah',
            'avatar' => '*',
        ])->participants()->attach([1, 2, 3]);
        User::findOrFail(5)->flatsOwned()->create([
            'label' => 'Alcoloc',
            'avatar' => '*',
        ])->participants()->attach([1, 4, 5]);
    }
}
