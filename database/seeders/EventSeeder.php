<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\TicketsType;
use Database\Factories\EventFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Supprimer tous élément de la table events
        DB::table('events')->truncate();

        // Supprimer tous élément de la table tickets_types
        DB::table('tickets_types')->truncate();


        // Créer 100 évement et pour chacun d'eux ratacher 3 types de ticket
        Event::factory(100)
            ->create()
            ->each(function (Event $event){
                TicketsType::factory(3)->create([
                    'event_id' => $event->id,
                ]);
            });
    }
}

