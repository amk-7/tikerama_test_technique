<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::create([
        //     'firstname' => 'abdoul Malik',
        //     'lastname' => 'KONDI',
        //     'business' => 'Stark Industrie',
        //     'email' => 'abdoulmalikkondi8@gmail.com',
        //     'town' => 'Sokodé',
        //     'address' => 'Sokodé',
        //     'password' => Hash::make('123456789'),
        // ]);

        $this->call([
            EventSeeder::class,
            TicketSeeder::class,
        ]);
    }
}
