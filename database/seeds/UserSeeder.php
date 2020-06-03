<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Emma',
            'email' => 'emma@gmail.com',
            'password' => Hash::make('password'),
            'avatar' => '*',
            'api_token' => '12345678901234567890123456789012345678901234567890123456789012345678901234567890',
        ]);
        usleep(300000);

        User::create([
            'name' => 'Marie',
            'email' => 'marie@gmail.com',
            'password' => Hash::make('password'),
            'avatar' => '*',
            'api_token' => '12345678901234567890123456789012345678901234567890123456789012345678901234567891',
        ]);
        usleep(300000);

        User::create([
            'name' => 'Sarah',
            'email' => 'sarah@gmail.com',
            'password' => Hash::make('password'),
            'avatar' => null,
            'api_token' => Str::random(80),
        ]);
        usleep(300000);

        User::create([
            'name' => 'Mehdi',
            'email' => 'mehdi@gmail.com',
            'password' => Hash::make('password'),
            'avatar' => null,
            'api_token' => Str::random(80),
        ]);
        usleep(300000);

        User::create([
            'name' => 'Emilie',
            'email' => 'emilie@gmail.com',
            'password' => Hash::make('password'),
            'avatar' => null,
            'api_token' => Str::random(80),
        ]);
        usleep(300000);

        User::create([
            'name' => 'Jade',
            'email' => 'jade@gmail.com',
            'password' => Hash::make('password'),
            'avatar' => null,
            'api_token' => Str::random(80),
        ]);
        usleep(300000);
    }
}
