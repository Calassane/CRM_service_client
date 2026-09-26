<x-app-layout>
    <x-slot name="header">
        <p class="eyebrow">Support & suivi</p>
        <h1 class="page-title">Modifier l’appel</h1>
        <p class="page-subtitle">Corrigez la qualification, le statut ou les notes de l’interaction.</p>
    </x-slot>
    <div class="page-container">
        <div class="mx-auto max-w-5xl">
            <form method="POST" action="{{ route('customer-calls.update', $customerCall) }}" class="panel p-5 sm:p-8">
                @csrf
                @method('PUT')
                @include('customer-calls.partials.form')
            </form>
        </div>
    </div>
</x-app-layout>
