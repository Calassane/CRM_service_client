<?php

namespace Database\Seeders;

use App\Enums\CallDirection;
use App\Enums\CallReason;
use App\Enums\CallStatus;
use App\Models\Client;
use App\Models\CustomerCall;
use App\Models\Tag;
use App\Models\User;
use Carbon\CarbonImmutable;
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
        if (CustomerCall::query()->exists()) {
            return;
        }

        $clients = Client::query()->with('reservations')->get();
        $agentIds = User::query()->orderBy('id')->pluck('id')->values();
        $tagIds = Tag::query()->pluck('id')->all();
        $reasons = CallReason::cases();
        $statuses = [
            CallStatus::Resolved,
            CallStatus::Resolved,
            CallStatus::Resolved,
            CallStatus::Pending,
            CallStatus::Escalated,
        ];
        $notes = [
            'Le client demande une confirmation de disponibilité du véhicule.',
            'Le client souhaite modifier les horaires de prise en charge.',
            'Le paiement mobile money doit être vérifié par le service financier.',
            'Une relance est prévue avec le client.',
            'La demande a été traitée pendant l’appel.',
        ];
        $today = CarbonImmutable::today();

        for ($index = 0; $index < 75; $index++) {
            $client = $clients[$index % $clients->count()];
            $reservation = $index % 3 === 0
                ? null
                : $client->reservations[$index % $client->reservations->count()];

            $customerCall = CustomerCall::query()->create([
                'client_id' => $client->id,
                'reservation_id' => $reservation?->id,
                'agent_id' => $agentIds[$index % $agentIds->count()],
                'direction' => $index % 3 === 0
                    ? CallDirection::Outbound
                    : CallDirection::Inbound,
                'reason' => $reasons[$index % count($reasons)],
                'started_at' => $today
                    ->subDays($index % 45)
                    ->setTime(8 + ($index % 10), ($index * 7) % 60),
                'duration_seconds' => 45 + (($index * 37) % 1155),
                'status' => $statuses[$index % count($statuses)],
                'notes' => $notes[$index % count($notes)],
            ]);

            $selectedTagIds = [];

            for ($tagIndex = 0; $tagIndex < $index % 4; $tagIndex++) {
                $selectedTagIds[] = $tagIds[($index + $tagIndex) % count($tagIds)];
            }

            $customerCall->tags()->sync($selectedTagIds);
        }
    }
}
