<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Reservation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clientIds = Client::query()->pluck('id');

        Reservation::factory()
            ->count(25)
            ->state(fn (): array => ['client_id' => $clientIds->random()])
            ->create();
    }
}
