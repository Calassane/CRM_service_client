@if ($errors->any())
    <div class="mb-6 rounded-md border border-red-200 bg-red-50 p-4 text-sm text-red-700">
        <p class="font-semibold">Le formulaire contient des erreurs.</p>
        <ul class="mt-2 list-disc pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

<div class="grid gap-6 md:grid-cols-2">
    <div>
        <label for="first_name" class="block text-sm font-medium text-gray-700">Prénom</label>
        <input id="first_name" name="first_name" required maxlength="80" value="{{ old('first_name', $client?->first_name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    <div>
        <label for="last_name" class="block text-sm font-medium text-gray-700">Nom</label>
        <input id="last_name" name="last_name" required maxlength="80" value="{{ old('last_name', $client?->last_name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    <div>
        <label for="phone" class="block text-sm font-medium text-gray-700">Téléphone</label>
        <input id="phone" name="phone" required maxlength="30" value="{{ old('phone', $client?->phone) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input id="email" name="email" type="email" maxlength="255" value="{{ old('email', $client?->email) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    <div>
        <label for="city" class="block text-sm font-medium text-gray-700">Ville</label>
        <input id="city" name="city" required maxlength="100" value="{{ old('city', $client?->city ?? 'Abidjan') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    <div>
        <label for="address" class="block text-sm font-medium text-gray-700">Adresse</label>
        <input id="address" name="address" maxlength="255" value="{{ old('address', $client?->address) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
</div>

<div class="mt-8 flex justify-end gap-3 border-t border-gray-100 pt-6">
    <a href="{{ $client ? route('clients.show', $client) : route('clients.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Annuler</a>
    <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">{{ $client ? 'Mettre à jour' : 'Ajouter le client' }}</button>
</div>
