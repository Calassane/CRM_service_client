@if ($errors->any())
    <div class="mb-6 alert-danger">
        <p class="font-semibold">Le formulaire contient des erreurs.</p>
        <ul class="mt-2 list-disc pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

<div class="grid gap-6 md:grid-cols-2">
    <div class="md:col-span-2">
        <label for="client_id" class="form-label">Client</label>
        <select id="client_id" name="client_id" required class="form-control">
            <option value="">Sélectionner un client</option>
            @foreach ($clients as $client)
                <option value="{{ $client->id }}" @selected(old('client_id', $reservation?->client_id ?? $selectedClientId) == $client->id)>{{ $client->full_name }} — {{ $client->phone }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="reference" class="form-label">Référence</label>
        <input id="reference" name="reference" required maxlength="30" value="{{ old('reference', $reservation?->reference) }}" placeholder="BR-000001" class="form-control">
    </div>
    <div>
        <label for="vehicle" class="form-label">Véhicule</label>
        <input id="vehicle" name="vehicle" required maxlength="255" value="{{ old('vehicle', $reservation?->vehicle) }}" placeholder="Toyota RAV4" class="form-control">
    </div>
    <div>
        <label for="start_date" class="form-label">Date de début</label>
        <input id="start_date" name="start_date" type="date" required value="{{ old('start_date', $reservation?->start_date?->format('Y-m-d')) }}" class="form-control">
    </div>
    <div>
        <label for="end_date" class="form-label">Date de fin</label>
        <input id="end_date" name="end_date" type="date" required value="{{ old('end_date', $reservation?->end_date?->format('Y-m-d')) }}" class="form-control">
    </div>
    <div>
        <label for="status" class="form-label">Statut</label>
        <select id="status" name="status" required class="form-control">
            @foreach ($statusOptions as $option)
                <option value="{{ $option['value'] }}" @selected(old('status', $reservation?->status?->value ?? $defaultStatus) === $option['value'])>{{ $option['label'] }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="mt-8 flex justify-end gap-3 border-t border-slate-100 pt-6">
    <a href="{{ $reservation ? route('reservations.show', $reservation) : route('reservations.index') }}" class="btn-secondary">Annuler</a>
    <button type="submit" class="btn-primary">{{ $reservation ? 'Mettre à jour' : 'Créer la réservation' }}</button>
</div>
