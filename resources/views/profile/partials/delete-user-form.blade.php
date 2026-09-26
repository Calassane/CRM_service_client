<section class="space-y-5">
    <header>
        <h2 class="text-base font-bold text-red-700">Zone sensible</h2>
        <p class="mt-1 text-sm leading-6 text-slate-500">La suppression du compte est définitive. Toutes les données associées seront perdues.</p>
    </header>

    <x-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">Supprimer mon compte</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 sm:p-8">
            @csrf
            @method('delete')

            <h2 class="text-xl font-extrabold text-slate-950">Confirmer la suppression du compte</h2>
            <p class="mt-2 text-sm leading-6 text-slate-500">Saisissez votre mot de passe pour confirmer cette action irréversible.</p>

            <div class="mt-6">
                <x-input-label for="password" value="Mot de passe" class="sr-only" />
                <x-text-input id="password" name="password" type="password" class="w-full" placeholder="Mot de passe" />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">Annuler</x-secondary-button>
                <x-danger-button>Supprimer définitivement</x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
