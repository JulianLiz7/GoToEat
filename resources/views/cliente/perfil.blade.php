<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Mi Perfil — GoToEat</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&family=Inter:wght@400;500;600&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .glass-nav { backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }
    </style>
</head>
<body class="bg-background text-on-surface antialiased font-body-md">

<aside class="hidden md:flex flex-col h-screen w-64 bg-surface-container-low border-r border-outline-variant/20 fixed left-0 top-0 z-50 shadow-md">
    <div class="px-6 py-6 border-b border-outline-variant/20">
        <a href="{{ route('explorar') }}" class="flex items-center gap-3">
            <div class="w-10 h-10 bg-primary-container rounded-xl flex items-center justify-center shadow-md">
                <span class="material-symbols-outlined text-white text-[22px]" style="font-variation-settings:'FILL' 1">restaurant</span>
            </div>
            <div>
                <p class="font-black text-xl text-primary leading-none">GoToEat</p>
                <p class="text-[10px] text-on-surface-variant uppercase tracking-widest mt-0.5">Gastronomía</p>
            </div>
        </a>
    </div>
    <nav class="flex-1 px-3 py-4 space-y-1">
        @foreach([['explorar','explore','Explorar'],['reservas','calendar_month','Mis Reservas'],['perfil','person','Mi Perfil']] as [$r,$i,$l])
        @php $a = request()->routeIs($r); @endphp
        <a href="{{ route($r) }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ $a ? 'bg-primary-container text-white font-bold shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
            <span class="material-symbols-outlined text-[22px]" @if($a) style="font-variation-settings:'FILL' 1" @endif>{{ $i }}</span>
            <span class="text-sm">{{ $l }}</span>
        </a>
        @endforeach
    </nav>
    <div class="px-3 py-4 border-t border-outline-variant/20 space-y-1">
        <a href="{{ route('perfil') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-surface-container-high transition-all">
            <img src="{{ auth()->user()->avatarUrl() }}" class="w-8 h-8 rounded-full object-cover border border-outline-variant/30" alt=""/>
            <div class="min-w-0">
                <p class="text-sm font-semibold text-on-surface truncate">{{ auth()->user()->name }}</p>
                <p class="text-[11px] text-on-surface-variant truncate">{{ auth()->user()->email }}</p>
            </div>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-error hover:bg-red-50 transition-all text-sm">
                <span class="material-symbols-outlined text-[20px]">logout</span>
                Cerrar sesión
            </button>
        </form>
    </div>
</aside>

<main class="md:ml-64 min-h-screen flex flex-col pb-20 md:pb-0">

    <header class="sticky top-0 z-40 glass-nav bg-surface/80 border-b border-outline-variant/20 shadow-sm">
        <div class="max-w-4xl mx-auto px-4 md:px-8 h-16 flex items-center justify-between">
            <h1 class="font-bold text-lg text-on-surface">Mi Perfil</h1>
            <img src="{{ $user->avatarUrl() }}" class="w-9 h-9 rounded-full object-cover" alt=""/>
        </div>
    </header>

    @if(session('success'))
    <div class="max-w-4xl mx-auto px-4 md:px-8 pt-4 w-full">
        <div class="p-4 bg-secondary/10 border border-secondary/20 rounded-xl flex items-center gap-3">
            <span class="material-symbols-outlined text-secondary" style="font-variation-settings:'FILL' 1">check_circle</span>
            <p class="text-sm font-semibold text-secondary">{{ session('success') }}</p>
        </div>
    </div>
    @endif
    @if(session('success_password'))
    <div class="max-w-4xl mx-auto px-4 md:px-8 pt-4 w-full">
        <div class="p-4 bg-secondary/10 border border-secondary/20 rounded-xl flex items-center gap-3">
            <span class="material-symbols-outlined text-secondary" style="font-variation-settings:'FILL' 1">lock</span>
            <p class="text-sm font-semibold text-secondary">{{ session('success_password') }}</p>
        </div>
    </div>
    @endif

    <div class="max-w-4xl mx-auto px-4 md:px-8 py-8 w-full">

        {{-- Header de perfil --}}
        <div class="bg-gradient-to-br from-primary-container to-primary rounded-2xl p-8 mb-8 text-white relative overflow-hidden">
            <div class="absolute inset-0 opacity-10" style="background-image:radial-gradient(circle at 1px 1px,white 1px,transparent 0);background-size:28px 28px"></div>
            <div class="relative z-10 flex items-center gap-6">
                {{-- Avatar clickeable --}}
                <label for="avatarInput" class="cursor-pointer group relative shrink-0">
                    <img src="{{ $user->avatarUrl() }}"
                         class="w-20 h-20 rounded-2xl object-cover border-3 border-white/50 shadow-lg group-hover:opacity-80 transition-opacity"
                         alt="{{ $user->name }}"/>
                    <div class="absolute inset-0 bg-black/30 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-[24px]">photo_camera</span>
                    </div>
                    <div class="absolute -bottom-1 -right-1 w-7 h-7 bg-white rounded-full flex items-center justify-center shadow-md">
                        <span class="material-symbols-outlined text-primary-container text-[16px]">edit</span>
                    </div>
                </label>
                <div>
                    <h2 class="font-black text-2xl">{{ $user->name }}</h2>
                    <p class="text-white/80 text-sm">{{ $user->email }}</p>
                    <div class="flex items-center gap-3 mt-2">
                        <span class="flex items-center gap-1.5 text-white/70 text-xs">
                            <span class="material-symbols-outlined text-[14px]">calendar_month</span>
                            {{ $reservationsCount }} reservas
                        </span>
                        <span class="flex items-center gap-1.5 text-white/70 text-xs">
                            <span class="material-symbols-outlined text-[14px]">star</span>
                            Comensal GoToEat
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Datos Personales --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-on-surface mb-5 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary-container text-[22px]">manage_accounts</span>
                    Datos Personales
                </h3>
                <form method="POST" action="{{ route('perfil.update') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <input type="file" id="avatarInput" name="avatar" accept="image/*" class="hidden"
                           onchange="this.form.submit()"/>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5">Nombre completo *</label>
                        <input name="name" type="text" required value="{{ old('name', $user->name) }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all"/>
                        @error('name')<p class="text-xs text-error mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5">Correo electrónico *</label>
                        <input name="email" type="email" required value="{{ old('email', $user->email) }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all"/>
                        @error('email')<p class="text-xs text-error mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5">Teléfono</label>
                        <input name="phone" type="tel" placeholder="+57 300 000 0000"
                               value="{{ old('phone', $user->phone) }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all"/>
                        @error('phone')<p class="text-xs text-error mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5">Fecha de nacimiento</label>
                        <input name="date_of_birth" type="date"
                               value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all"/>
                        @error('date_of_birth')<p class="text-xs text-error mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Preferencias alimentarias --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-2">Preferencias Alimentarias / Alergias</label>
                        @php
                        $prefs = ['Vegetariano','Vegano','Sin gluten','Sin lactosa','Sin mariscos','Sin nueces','Halal','Kosher','Bajo en sodio','Bajo en calorías'];
                        $userPrefs = $user->dietary_preferences ?? [];
                        @endphp
                        <div class="flex flex-wrap gap-2">
                            @foreach($prefs as $pref)
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="checkbox" name="dietary_preferences[]"
                                       value="{{ $pref }}"
                                       {{ in_array($pref, $userPrefs) ? 'checked' : '' }}
                                       class="hidden peer"/>
                                <span class="px-3 py-1.5 rounded-full text-xs font-semibold border transition-all cursor-pointer
                                             peer-checked:bg-primary-container peer-checked:text-white peer-checked:border-primary-container
                                             border-gray-200 text-gray-500 hover:border-primary-container hover:text-primary-container">
                                    {{ $pref }}
                                </span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full py-2.5 bg-primary-container text-white rounded-xl font-bold text-sm hover:bg-primary active:scale-[0.97] transition-all shadow-sm shadow-orange-200">
                        Guardar Cambios
                    </button>
                </form>
            </div>

            {{-- Seguridad + Info --}}
            <div class="space-y-5">
                {{-- Cambio de contraseña --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-bold text-on-surface mb-5 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary-container text-[22px]">lock</span>
                        Seguridad de la Cuenta
                    </h3>
                    <form method="POST" action="{{ route('perfil.password') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5">Contraseña actual *</label>
                            <input name="current_password" type="password" placeholder="••••••••"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all"/>
                            @error('current_password')<p class="text-xs text-error mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5">Nueva contraseña *</label>
                            <input name="password" type="password" placeholder="Mínimo 8 caracteres"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all"/>
                            @error('password')<p class="text-xs text-error mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5">Confirmar nueva contraseña *</label>
                            <input name="password_confirmation" type="password" placeholder="••••••••"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all"/>
                        </div>
                        <button type="submit"
                                class="w-full py-2.5 bg-white border border-primary-container text-primary-container rounded-xl font-bold text-sm hover:bg-primary-container hover:text-white active:scale-[0.97] transition-all">
                            Cambiar Contraseña
                        </button>
                    </form>
                </div>

                {{-- Info de cuenta --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-bold text-on-surface mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary-container text-[22px]">badge</span>
                        Mi Cuenta
                    </h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-center py-2 border-b border-gray-50">
                            <span class="text-gray-500">Miembro desde</span>
                            <span class="font-semibold">{{ $user->created_at->locale('es')->isoFormat('D MMM, YYYY') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-50">
                            <span class="text-gray-500">Total reservas</span>
                            <span class="font-semibold text-primary-container">{{ $reservationsCount }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-500">Estado</span>
                            <span class="font-bold text-secondary flex items-center gap-1">
                                <span class="w-2 h-2 bg-secondary rounded-full"></span>
                                Activo
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Zona peligrosa --}}
                <div class="bg-red-50 border border-red-100 rounded-2xl p-5">
                    <h4 class="font-bold text-sm text-error mb-2 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">warning</span>
                        Zona peligrosa
                    </h4>
                    <p class="text-xs text-gray-500 mb-3">Cerrar sesión en todos los dispositivos.</p>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-white border border-red-200 text-error rounded-xl text-sm font-semibold hover:bg-red-50 transition-colors">
                            <span class="material-symbols-outlined text-[18px]">logout</span>
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<nav class="md:hidden fixed bottom-0 left-0 right-0 glass-nav bg-surface/90 border-t border-outline-variant/20 flex justify-around items-center py-2 z-50">
    @foreach([['explorar','explore','Explorar'],['reservas','calendar_month','Reservas'],['perfil','person','Perfil']] as [$r,$i,$l])
    @php $a = request()->routeIs($r); @endphp
    <a href="{{ route($r) }}" class="flex flex-col items-center gap-0.5 px-4 py-1 {{ $a ? 'text-primary' : 'text-on-surface-variant' }}">
        <span class="material-symbols-outlined text-[22px]" @if($a) style="font-variation-settings:'FILL' 1" @endif>{{ $i }}</span>
        <span class="text-[10px] font-semibold">{{ $l }}</span>
    </a>
    @endforeach
</nav>

</body>
</html>
