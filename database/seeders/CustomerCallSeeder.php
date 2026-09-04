<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\CustomerCall;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerCallSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = Client::query()->with('reservations')->get();
        $agentIds = User::query()->pluck('id');
        $tagIds = Tag::query()->pluck('id')->all();

        CustomerCall::factory()
            ->count(75)
            ->state(function () use ($clients, $agentIds): array {
                $client = $clients->random();
                $reservationId = null;

                if (fake()->boolean(65) && $client->reservations->isNotEmpty()) {
                    $reservationId = $client->reservations->random()->id;
                }

                return [
                    'client_id' => $client->id,
                    'reservation_id' => $reservationId,
                    'agent_id' => $agentIds->random(),
                ];
            })
            ->create()
            ->each(function (CustomerCall $customerCall) use ($tagIds): void {
                $customerCall->tags()->attach(
                    fake()->randomElements($tagIds, fake()->numberBetween(0, 3)),
                );
            });
    }
}
