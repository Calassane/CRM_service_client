@if ($errors->any())
    <div class="mb-6 rounded-md border border-red-200 bg-red-50 p-4 text-sm text-red-700">
        <p class="font-semibold">Le formulaire contient des erreurs.</p>
        <ul class="mt-2 list-disc pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

<div class="grid gap-6 md:grid-cols-2">
    <div class="md:col-span-2">
        <label for="client_id" class="block text-sm font-medium text-gray-700">Client</label>
        <select id="client_id" name="client_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <option value="">Sélectionner un client</option>
            @foreach ($clients as $client)
                <option value="{{ $client->id }}" @selected(old('client_id', $reservation?->client_id ?? $selectedClientId) == $client->id)>{{ $client->full_name }} — {{ $client->phone }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="reference" class="block text-sm font-medium text-gray-700">Référence</label>
        <input id="reference" name="reference" required maxlength="30" value="{{ old('reference', $reservation?->reference) }}" placeholder="BR-000001" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    <div>
        <label for="vehicle" class="block text-sm font-medium text-gray-700">Véhicule</label>
        <input id="vehicle" name="vehicle" required maxlength="255" value="{{ old('vehicle', $reservation?->vehicle) }}" placeholder="Toyota RAV4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    <div>
        <label for="start_date" class="block text-sm font-medium text-gray-700">Date de début</label>
        <input id="start_date" name="start_date" type="date" required value="{{ old('start_date', $reservation?->start_date?->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    <div>
        <label for="end_date" class="block text-sm font-medium text-gray-700">Date de fin</label>
        <input id="end_date" name="end_date" type="date" required value="{{ old('end_date', $reservation?->end_date?->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    <div>
        <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
        <select id="status" name="status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            @foreach ($statusOptions as $option)
                <option value="{{ $option['value'] }}" @selected(old('status', $reservation?->status?->value ?? $defaultStatus) === $option['value'])>{{ $option['label'] }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="mt-8 flex justify-end gap-3 border-t border-gray-100 pt-6">
    <a href="{{ $reservation ? route('reservations.show', $reservation) : route('reservations.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Annuler</a>
    <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">{{ $reservation ? 'Mettre à jour' : 'Créer la réservation' }}</button>
</div>
