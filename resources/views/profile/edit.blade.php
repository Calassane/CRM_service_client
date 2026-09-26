<x-app-layout>
    <x-slot name="header">
        <p class="eyebrow">Mon espace</p>
        <h1 class="page-title">Paramètres du profil</h1>
        <p class="page-subtitle">Gérez vos informations personnelles et la sécurité de votre compte.</p>
    </x-slot>

    <div class="page-container">
        <div class="mx-auto grid max-w-6xl gap-6 lg:grid-cols-2">
            <div class="panel p-5 sm:p-7">
                @include('profile.partials.update-profile-information-form')
            </div>
            <div class="panel p-5 sm:p-7">
                @include('profile.partials.update-password-form')
            </div>
            <div class="panel border-red-100 p-5 sm:p-7 lg:col-span-2">
                <div class="max-w-2xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
