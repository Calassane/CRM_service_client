<?php

namespace App\Actions\Clients;

use App\Models\Client;
use Illuminate\Validation\ValidationException;

class DeleteClient
{
    public function handle(Client $client): void
    {
        if ($client->reservations()->exists() || $client->customerCalls()->exists()) {
            throw ValidationException::withMessages([
                'client' => 'Ce client ne peut pas être supprimé car il possède un historique de réservations ou d’appels.',
            ]);
        }

        $client->delete();
    }
}
