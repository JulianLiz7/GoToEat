<nav x-data="{ open: false }" class="bg-white border-b border-outline-variant shadow-sm sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- Logo + Links de escritorio --}}
            <div class="flex items-center gap-6">
                <a href="{{ route('dashboard') }}"
                   class="text-xl font-bold text-primary-container font-heading tracking-tight shrink-0">
                    GoToEat
                </a>

                <div class="hidden sm:flex items-center gap-1">
                    @if(auth()->user()?->hasRole('admin'))
                        <a href="{{ route('dashboard') }}"
                           class="px-3 py-2 rounded-lg text-sm font-medium transition-all
                                  {{ request()->routeIs('dashboard') ? 'bg-orange-50 text-primary-container' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface' }}">
                            <span class="material-symbols-outlined text-base align-middle mr-1">dashboard</span>Panel Principal
                        </a>
                        <span class="px-3 py-2 text-sm font-medium text-outline cursor-not-allowed" title="Próximamente">
                            <span class="material-symbols-outlined text-base align-middle mr-1">menu_book</span>Menú
                        </span>
                        <span class="px-3 py-2 text-sm font-medium text-outline cursor-not-allowed" title="Próximamente">
                            <span class="material-symbols-outlined text-base align-middle mr-1">table_restaurant</span>Mesas
                        </span>
                        <span class="px-3 py-2 text-sm font-medium text-outline cursor-not-allowed" title="Próximamente">
                            <span class="material-symbols-outlined text-base align-middle mr-1">groups</span>Personal
                        </span>
                    @else
                        <a href="{{ route('dashboard') }}"
                           class="px-3 py-2 rounded-lg text-sm font-medium transition-all
                                  {{ request()->routeIs('dashboard') ? 'bg-orange-50 text-primary-container' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface' }}">
                            <span class="material-symbols-outlined text-base align-middle mr-1">home</span>Inicio
                        </a>
                        <a href="{{ route('dashboard') }}"
                           class="px-3 py-2 rounded-lg text-sm font-medium text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface transition-all">
                            <span class="material-symbols-outlined text-base align-middle mr-1">explore</span>Explorar
                        </a>
                    @endif
                </div>
            </div>

            {{-- Badge de rol + dropdown usuario --}}
            <div class="hidden sm:flex items-center gap-3">
                @if(auth()->user()?->hasRole('admin'))
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-orange-100 text-primary-container">Admin</span>
                @else
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-secondary">Cliente</span>
                @endif

                <div x-data="{ profileOpen: false }" class="relative">
                    <button @click="profileOpen = !profileOpen"
                            class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-surface-container-low transition-all">
                        <div class="w-8 h-8 rounded-full bg-primary-container flex items-center justify-center text-white font-bold text-sm shrink-0">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <span class="text-sm font-medium text-on-surface max-w-[130px] truncate">{{ Auth::user()->name }}</span>
                        <span class="material-symbols-outlined text-outline text-lg">expand_more</span>
                    </button>

                    <div x-show="profileOpen" x-cloak @click.outside="profileOpen = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="absolute right-0 top-full mt-2 w-52 bg-white rounded-xl shadow-lg border border-outline-variant py-1 z-50">

                        <div class="px-4 py-2.5 border-b border-outline-variant">
                            <p class="text-sm font-semibold text-on-surface truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-on-surface-variant truncate">{{ Auth::user()->email }}</p>
                        </div>

                        <a href="{{ route('profile.edit') }}"
                           class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-on-surface hover:bg-surface-container-low transition-all">
                            <span class="material-symbols-outlined text-lg text-outline">manage_accounts</span>Mi perfil
                        </a>

                        <div class="my-1 border-t border-outline-variant"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm text-error hover:bg-error-container/30 transition-all">
                                <span class="material-symbols-outlined text-lg">logout</span>Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Hamburger móvil --}}
            <div class="flex items-center sm:hidden">
                <button @click="open = !open" class="p-2 rounded-lg text-outline hover:bg-surface-container-low transition-all">
                    <span class="material-symbols-outlined" x-text="open ? 'close' : 'menu'">menu</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Menú móvil --}}
    <div x-show="open" x-cloak x-transition class="sm:hidden border-t border-outline-variant bg-white">
        <div class="px-4 py-3 space-y-1">
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm font-medium text-on-surface hover:bg-surface-container-low">
                <span class="material-symbols-outlined text-lg text-outline">dashboard</span>Panel Principal
            </a>
            <a href="{{ route('profile.edit') }}"
               class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm font-medium text-on-surface hover:bg-surface-container-low">
                <span class="material-symbols-outlined text-lg text-outline">manage_accounts</span>Mi perfil
            </a>
            <div class="border-t border-outline-variant my-2"></div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm font-medium text-error hover:bg-error-container/30">
                    <span class="material-symbols-outlined text-lg">logout</span>Cerrar sesión
                </button>
            </form>
        </div>
        <div class="px-4 py-3 border-t border-outline-variant bg-surface-container-low">
            <p class="text-sm font-semibold text-on-surface">{{ Auth::user()->name }}</p>
            <p class="text-xs text-on-surface-variant">{{ Auth::user()->email }}</p>
        </div>
    </div>
</nav>
