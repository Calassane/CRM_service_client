<x-guest-layout>
    <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-panel sm:p-9">
        <div>
            <p class="eyebrow">Accès sécurisé</p>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-950">Heureux de vous revoir.</h1>
            <p class="mt-2 text-sm leading-6 text-slate-500">Connectez-vous au centre de pilotage du service client.</p>
        </div>

        <div class="mt-7 rounded-2xl border border-brand-100 bg-brand-50 p-4">
            <div class="flex items-start gap-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-brand-600 text-white">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2Zm2-12V7a4 4 0 1 1 8 0v2"/></svg>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-extrabold text-brand-950">Compte de démonstration</p>
                    <p class="mt-1 break-all text-xs font-semibold text-brand-800">demo@bollirental.africa</p>
                    <p class="text-xs font-semibold text-brand-800">BolliDemo2026!</p>
                </div>
            </div>
        </div>

        <x-auth-session-status class="mt-5 rounded-xl bg-emerald-50 p-3 text-sm text-emerald-800" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="mt-7 space-y-5">
            @csrf

            <div>
                <x-input-label for="email" value="Adresse e-mail" />
                <x-text-input id="email" class="w-full" type="email" name="email" :value="old('email', 'demo@bollirental.africa')" required autofocus autocomplete="username" placeholder="vous@entreprise.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <div class="flex items-center justify-between">
                    <x-input-label for="password" value="Mot de passe" />
                    @if (Route::has('password.request'))
                        <a class="mb-1.5 text-xs font-bold text-brand-600 transition hover:text-brand-800" href="{{ route('password.request') }}">Mot de passe oublié ?</a>
                    @endif
                </div>
                <x-text-input id="password" class="w-full" type="password" name="password" required autocomplete="current-password" placeholder="••••••••••••" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <label for="remember_me" class="flex cursor-pointer items-center gap-2.5 text-sm font-medium text-slate-600">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-brand-600 shadow-sm focus:ring-brand-500" name="remember">
                Se souvenir de moi
            </label>

            <x-primary-button class="w-full py-3">
                Se connecter
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 18 6-6-6-6"/></svg>
            </x-primary-button>
        </form>
    </div>

    <p class="mt-6 text-center text-xs text-slate-400">Plateforme interne · Accès réservé aux équipes Bolli Rental</p>
</x-guest-layout>
