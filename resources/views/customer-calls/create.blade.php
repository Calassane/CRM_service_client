<x-app-layout>
    <x-slot name="header">
        <p class="eyebrow">Support & suivi</p>
        <h1 class="page-title">Enregistrer un appel</h1>
        <p class="page-subtitle">Documentez l’échange pour garantir un suivi client sans rupture.</p>
    </x-slot>
    <div class="page-container">
        <div class="mx-auto max-w-5xl">
            <form method="POST" action="{{ route('customer-calls.store') }}" class="panel p-5 sm:p-8">
                @csrf
                @include('customer-calls.partials.form')
            </form>
        </div>
    </div>
</x-app-layout>
