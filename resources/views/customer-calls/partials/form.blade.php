@if ($errors->any())
    <div class="mb-6 alert-danger">
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
        <label for="client_id" class="form-label">Client</label>
        <select id="client_id" name="client_id" required class="form-control">
            <option value="">Sélectionner un client</option>
            @foreach ($clients as $client)
                <option value="{{ $client->id }}" @selected(old('client_id', $customerCall?->client_id ?? $selectedClientId) == $client->id)>
                    {{ $client->full_name }} — {{ $client->phone }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="reservation_id" class="form-label">Réservation liée</label>
        <select id="reservation_id" name="reservation_id" class="form-control">
            <option value="">Aucune réservation</option>
            @foreach ($reservations as $reservation)
                <option value="{{ $reservation->id }}" @selected(old('reservation_id', $customerCall?->reservation_id) == $reservation->id)>
                    {{ $reservation->reference }} — {{ $reservation->client->full_name }} — {{ $reservation->vehicle }}
                </option>
            @endforeach
        </select>
        <p class="form-help">La réservation doit appartenir au client sélectionné.</p>
    </div>

    <div>
        <label for="direction" class="form-label">Sens de l’appel</label>
        <select id="direction" name="direction" required class="form-control">
            @foreach ($directionOptions as $option)
                <option value="{{ $option['value'] }}" @selected(old('direction', $customerCall?->direction?->value ?? $defaultDirection) === $option['value'])>{{ $option['label'] }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="reason" class="form-label">Motif</label>
        <select id="reason" name="reason" required class="form-control">
            @foreach ($reasonOptions as $option)
                <option value="{{ $option['value'] }}" @selected(old('reason', $customerCall?->reason?->value ?? $defaultReason) === $option['value'])>{{ $option['label'] }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="started_at" class="form-label">Date et heure</label>
        <input id="started_at" name="started_at" type="datetime-local" required value="{{ old('started_at', $customerCall?->started_at?->format('Y-m-d\TH:i') ?? $defaultStartedAt) }}" class="form-control">
    </div>

    <div>
        <label for="duration_seconds" class="form-label">Durée en secondes</label>
        <input id="duration_seconds" name="duration_seconds" type="number" min="1" max="86400" required value="{{ old('duration_seconds', $customerCall?->duration_seconds) }}" class="form-control">
    </div>

    <div>
        <label for="status" class="form-label">Statut</label>
        <select id="status" name="status" required class="form-control">
            @foreach ($statusOptions as $option)
                <option value="{{ $option['value'] }}" @selected(old('status', $customerCall?->status?->value ?? $defaultStatus) === $option['value'])>{{ $option['label'] }}</option>
            @endforeach
        </select>
    </div>

    <fieldset>
        <legend class="form-label">Étiquettes</legend>
        <div class="mt-2 flex flex-wrap gap-3">
            @foreach ($tags as $tag)
                <label class="inline-flex cursor-pointer items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-semibold text-slate-700 transition hover:border-brand-200 hover:bg-brand-50">
                    <input type="checkbox" name="tag_ids[]" value="{{ $tag->id }}" @checked(in_array($tag->id, old('tag_ids', $selectedTagIds))) class="rounded border-slate-300 text-brand-600 shadow-sm focus:ring-brand-500">
                    {{ $tag->name }}
                </label>
            @endforeach
        </div>
    </fieldset>

    <div class="md:col-span-2">
        <label for="notes" class="form-label">Notes</label>
        <textarea id="notes" name="notes" rows="6" maxlength="5000" class="form-control">{{ old('notes', $customerCall?->notes) }}</textarea>
    </div>
</div>

<div class="mt-8 flex items-center justify-end gap-3 border-t border-slate-100 pt-6">
    <a href="{{ $customerCall ? route('customer-calls.show', $customerCall) : route('customer-calls.index') }}" class="btn-secondary">Annuler</a>
    <button type="submit" class="btn-primary">
        {{ $customerCall ? 'Mettre à jour' : 'Enregistrer l’appel' }}
    </button>
</div>
