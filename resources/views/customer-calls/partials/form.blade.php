@if ($errors->any())
    <div class="mb-6 rounded-md border border-red-200 bg-red-50 p-4 text-sm text-red-700">
        <p class="font-semibold">Le formulaire contient des erreurs.</p>
        <ul class="mt-2 list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid gap-6 md:grid-cols-2">
    <div>
        <label for="client_id" class="block text-sm font-medium text-gray-700">Client</label>
        <select id="client_id" name="client_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <option value="">Sélectionner un client</option>
            @foreach ($clients as $client)
                <option value="{{ $client->id }}" @selected(old('client_id', $customerCall?->client_id) == $client->id)>
                    {{ $client->full_name }} — {{ $client->phone }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="reservation_id" class="block text-sm font-medium text-gray-700">Réservation liée</label>
        <select id="reservation_id" name="reservation_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <option value="">Aucune réservation</option>
            @foreach ($reservations as $reservation)
                <option value="{{ $reservation->id }}" @selected(old('reservation_id', $customerCall?->reservation_id) == $reservation->id)>
                    {{ $reservation->reference }} — {{ $reservation->client->full_name }} — {{ $reservation->vehicle }}
                </option>
            @endforeach
        </select>
        <p class="mt-1 text-xs text-gray-500">La réservation doit appartenir au client sélectionné.</p>
    </div>

    <div>
        <label for="direction" class="block text-sm font-medium text-gray-700">Sens de l’appel</label>
        <select id="direction" name="direction" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            @foreach ($directionOptions as $option)
                <option value="{{ $option['value'] }}" @selected(old('direction', $customerCall?->direction?->value ?? $defaultDirection) === $option['value'])>{{ $option['label'] }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="reason" class="block text-sm font-medium text-gray-700">Motif</label>
        <select id="reason" name="reason" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            @foreach ($reasonOptions as $option)
                <option value="{{ $option['value'] }}" @selected(old('reason', $customerCall?->reason?->value ?? $defaultReason) === $option['value'])>{{ $option['label'] }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="started_at" class="block text-sm font-medium text-gray-700">Date et heure</label>
        <input id="started_at" name="started_at" type="datetime-local" required value="{{ old('started_at', $customerCall?->started_at?->format('Y-m-d\TH:i') ?? $defaultStartedAt) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>

    <div>
        <label for="duration_seconds" class="block text-sm font-medium text-gray-700">Durée en secondes</label>
        <input id="duration_seconds" name="duration_seconds" type="number" min="1" max="86400" required value="{{ old('duration_seconds', $customerCall?->duration_seconds) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>

    <div>
        <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
        <select id="status" name="status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            @foreach ($statusOptions as $option)
                <option value="{{ $option['value'] }}" @selected(old('status', $customerCall?->status?->value ?? $defaultStatus) === $option['value'])>{{ $option['label'] }}</option>
            @endforeach
        </select>
    </div>

    <fieldset>
        <legend class="block text-sm font-medium text-gray-700">Étiquettes</legend>
        <div class="mt-2 flex flex-wrap gap-3">
            @foreach ($tags as $tag)
                <label class="inline-flex items-center gap-2 rounded-full border border-gray-200 px-3 py-1.5 text-sm text-gray-700">
                    <input type="checkbox" name="tag_ids[]" value="{{ $tag->id }}" @checked(in_array($tag->id, old('tag_ids', $selectedTagIds))) class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    {{ $tag->name }}
                </label>
            @endforeach
        </div>
    </fieldset>

    <div class="md:col-span-2">
        <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
        <textarea id="notes" name="notes" rows="6" maxlength="5000" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('notes', $customerCall?->notes) }}</textarea>
    </div>
</div>

<div class="mt-8 flex items-center justify-end gap-3 border-t border-gray-100 pt-6">
    <a href="{{ $customerCall ? route('customer-calls.show', $customerCall) : route('customer-calls.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Annuler</a>
    <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
        {{ $customerCall ? 'Mettre à jour' : 'Enregistrer l’appel' }}
    </button>
</div>
