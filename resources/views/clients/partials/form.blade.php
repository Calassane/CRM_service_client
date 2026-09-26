@if ($errors->any())
    <div class="mb-6 alert-danger">
        <p class="font-semibold">Le formulaire contient des erreurs.</p>
        <ul class="mt-2 list-disc pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

<div class="grid gap-6 md:grid-cols-2">
    <div>
        <label for="first_name" class="form-label">Prénom</label>
        <input id="first_name" name="first_name" required maxlength="80" value="{{ old('first_name', $client?->first_name) }}" class="form-control">
    </div>
    <div>
        <label for="last_name" class="form-label">Nom</label>
        <input id="last_name" name="last_name" required maxlength="80" value="{{ old('last_name', $client?->last_name) }}" class="form-control">
    </div>
    <div>
        <label for="phone" class="form-label">Téléphone</label>
        <input id="phone" name="phone" required maxlength="30" value="{{ old('phone', $client?->phone) }}" class="form-control">
    </div>
    <div>
        <label for="email" class="form-label">Email</label>
        <input id="email" name="email" type="email" maxlength="255" value="{{ old('email', $client?->email) }}" class="form-control">
    </div>
    <div>
        <label for="city" class="form-label">Ville</label>
        <input id="city" name="city" required maxlength="100" value="{{ old('city', $client?->city ?? 'Abidjan') }}" class="form-control">
    </div>
    <div>
        <label for="address" class="form-label">Adresse</label>
        <input id="address" name="address" maxlength="255" value="{{ old('address', $client?->address) }}" class="form-control">
    </div>
</div>

<div class="mt-8 flex justify-end gap-3 border-t border-slate-100 pt-6">
    <a href="{{ $client ? route('clients.show', $client) : route('clients.index') }}" class="btn-secondary">Annuler</a>
    <button type="submit" class="btn-primary">{{ $client ? 'Mettre à jour' : 'Ajouter le client' }}</button>
</div>
