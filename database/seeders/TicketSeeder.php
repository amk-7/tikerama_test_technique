<?php

namespace Database\Seeders;

use App\Models\TicketsType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tickets_types')->truncate();
        TicketsType::factory(100)->create();
    }
}
