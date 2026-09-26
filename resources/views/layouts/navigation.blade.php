<div x-data="{ sidebarOpen: false }" @keydown.escape.window="sidebarOpen = false">
    <header class="fixed inset-x-0 top-0 z-40 flex h-16 items-center justify-between border-b border-slate-200 bg-white/95 px-4 backdrop-blur lg:hidden">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
            <x-application-logo class="h-9 w-9 text-brand-600" />
            <div>
                <span class="block text-lg font-extrabold leading-none tracking-tight text-slate-950">bolli.</span>
                <span class="text-[0.55rem] font-extrabold uppercase tracking-[0.2em] text-slate-400">Rental CRM</span>
            </div>
        </a>
        <button @click="sidebarOpen = true" type="button" class="icon-button" aria-label="Ouvrir le menu">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16"/>
            </svg>
        </button>
    </header>
    <div class="h-16 lg:hidden"></div>

    <div x-cloak x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-slate-950/50 backdrop-blur-sm lg:hidden"></div>

    <aside
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col border-r border-slate-800 bg-slate-950 text-white transition-transform duration-300 lg:translate-x-0"
    >
        <div class="flex h-20 items-center justify-between border-b border-white/10 px-6">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                <x-application-logo class="h-10 w-10 text-brand-500" />
                <div>
                    <span class="block text-xl font-extrabold leading-none tracking-tight">bolli.</span>
                    <span class="text-[0.6rem] font-extrabold uppercase tracking-[0.24em] text-slate-400">Rental CRM</span>
                </div>
            </a>
            <button @click="sidebarOpen = false" type="button" class="rounded-lg p-2 text-slate-400 hover:bg-white/10 hover:text-white lg:hidden" aria-label="Fermer le menu">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M6 6l12 12M18 6 6 18"/></svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto px-4 py-7">
            <p class="px-3 text-[0.62rem] font-extrabold uppercase tracking-[0.22em] text-slate-500">Navigation</p>
            <nav class="mt-3 space-y-1.5" aria-label="Navigation principale">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-brand-600 text-white shadow-lg shadow-brand-950/30' : 'text-slate-400 hover:bg-white/[.07] hover:text-white' }} flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-bold transition">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 13h6V4H4v9Zm0 7h6v-4H4v4Zm10 0h6v-9h-6v9Zm0-13h6V4h-6v3Z"/></svg>
                    Tableau de bord
                </a>
                <a href="{{ route('customer-calls.index') }}" class="{{ request()->routeIs('customer-calls.*') ? 'bg-brand-600 text-white shadow-lg shadow-brand-950/30' : 'text-slate-400 hover:bg-white/[.07] hover:text-white' }} flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-bold transition">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7.5 3.5h3l1.5 4-2.25 1.5a15.5 15.5 0 0 0 5.25 5.25l1.5-2.25 4 1.5v3A3.5 3.5 0 0 1 17 20C9.82 20 4 14.18 4 7a3.5 3.5 0 0 1 3.5-3.5Z"/></svg>
                    Appels clients
                </a>
                <a href="{{ route('clients.index') }}" class="{{ request()->routeIs('clients.*') ? 'bg-brand-600 text-white shadow-lg shadow-brand-950/30' : 'text-slate-400 hover:bg-white/[.07] hover:text-white' }} flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-bold transition">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 20v-1.5a4.5 4.5 0 0 0-4.5-4.5h-3A4.5 4.5 0 0 0 4 18.5V20m6-10a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm7-1a3 3 0 0 1 0 6m3 5v-1a4 4 0 0 0-2.5-3.7"/></svg>
                    Clients
                </a>
                <a href="{{ route('reservations.index') }}" class="{{ request()->routeIs('reservations.*') ? 'bg-brand-600 text-white shadow-lg shadow-brand-950/30' : 'text-slate-400 hover:bg-white/[.07] hover:text-white' }} flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-bold transition">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 5.5h14a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-11a2 2 0 0 1 2-2Zm2-3v5m10-5v5M3 10h18m-14 4h3m4 0h3"/></svg>
                    Réservations
                </a>
            </nav>

            <div class="mt-9 rounded-2xl border border-brand-500/20 bg-brand-500/10 p-4">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-brand-500/20 text-brand-300">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.4-6.4-1.5 1.5M7.1 16.9l-1.5 1.5m12.8 0-1.5-1.5M7.1 7.1 5.6 5.6M16 12a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z"/></svg>
                </div>
                <p class="mt-3 text-sm font-bold text-white">Centre de pilotage</p>
                <p class="mt-1 text-xs leading-5 text-slate-400">Une vue claire pour une meilleure expérience client.</p>
            </div>
        </div>

        <div class="border-t border-white/10 p-4">
            <div class="flex items-center gap-3 rounded-xl bg-white/[.04] p-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-brand-500 text-sm font-extrabold text-white">
                    {{ mb_strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-bold text-white">{{ Auth::user()->name }}</p>
                    <p class="truncate text-xs text-slate-500">{{ Auth::user()->email }}</p>
                </div>
                <a href="{{ route('profile.edit') }}" class="rounded-lg p-2 text-slate-500 transition hover:bg-white/10 hover:text-white" title="Mon profil">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15.5A3.5 3.5 0 1 0 12 8a3.5 3.5 0 0 0 0 7.5ZM19.4 15a1.7 1.7 0 0 0 .34 1.88l.06.06-2.83 2.83-.06-.06A1.7 1.7 0 0 0 15 19.4a1.7 1.7 0 0 0-1 .6 1.7 1.7 0 0 0-.4 1.1V21h-4v-.09A1.7 1.7 0 0 0 8.6 19.4a1.7 1.7 0 0 0-1.88.34l-.06.06-2.83-2.83.06-.06A1.7 1.7 0 0 0 4.2 15a1.7 1.7 0 0 0-.6-1 1.7 1.7 0 0 0-1.1-.4H2.4v-4h.09A1.7 1.7 0 0 0 4.2 8.6a1.7 1.7 0 0 0-.34-1.88l-.06-.06 2.83-2.83.06.06A1.7 1.7 0 0 0 8.6 4.2a1.7 1.7 0 0 0 1-.6 1.7 1.7 0 0 0 .4-1.1V2.4h4v.09A1.7 1.7 0 0 0 15 4.2a1.7 1.7 0 0 0 1.88-.34l.06-.06 2.83 2.83-.06.06A1.7 1.7 0 0 0 19.4 8.6c.38.28.73.62 1 .6.28.28.62.38 1.1.4h.09v4h-.09a1.7 1.7 0 0 0-1.1.4 1.7 1.7 0 0 0-.6 1Z"/></svg>
                </a>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-bold text-slate-400 transition hover:bg-white/[.07] hover:text-white">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 17l5-5-5-5m5 5H3m10-9h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6"/></svg>
                    Se déconnecter
                </button>
            </form>
        </div>
    </aside>
</div>
