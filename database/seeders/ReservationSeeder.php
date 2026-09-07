<?php

namespace Database\Seeders;

use App\Enums\ReservationStatus;
use App\Models\Client;
use App\Models\Reservation;
use Carbon\CarbonImmutable;
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
        $clients = Client::query()->orderBy('id')->get();
        $vehicles = [
            'Hyundai Tucson',
            'Kia Sportage',
            'Mitsubishi Pajero',
            'Toyota Corolla',
            'Toyota Land Cruiser Prado',
            'Toyota RAV4',
            'Toyota Hiace',
        ];
        $statuses = ReservationStatus::cases();
        $today = CarbonImmutable::today();

        for ($index = 0; $index < 25; $index++) {
            $startDate = $today->addDays(($index - 15) * 3);

            Reservation::query()->updateOrCreate(
                ['reference' => sprintf('BR-DEMO-%03d', $index + 1)],
                [
                    'client_id' => $clients[$index % $clients->count()]->id,
                    'vehicle' => $vehicles[$index % count($vehicles)],
                    'start_date' => $startDate,
                    'end_date' => $startDate->addDays(2 + ($index % 8)),
                    'status' => $statuses[$index % count($statuses)],
                ],
            );
        }
    }
}
