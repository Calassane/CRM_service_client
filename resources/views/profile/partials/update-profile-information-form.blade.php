<section>
    <header>
        <h2 class="panel-title">Informations personnelles</h2>
        <p class="panel-description">Modifiez votre nom et votre adresse e-mail professionnelle.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">@csrf</form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" value="Nom complet" />
            <x-text-input id="name" name="name" type="text" class="w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" value="Adresse e-mail" />
            <x-text-input id="email" name="email" type="email" class="w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 rounded-xl bg-amber-50 p-3 text-sm text-amber-800">
                    Votre adresse e-mail n’est pas vérifiée.
                    <button form="send-verification" class="font-bold underline">Renvoyer le lien de vérification.</button>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-bold text-emerald-700">Un nouveau lien de vérification a été envoyé.</p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-1">
            <x-primary-button>Enregistrer</x-primary-button>
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm font-semibold text-emerald-600">Modifications enregistrées.</p>
            @endif
        </div>
    </form>
</section>
