<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>{{ $restaurant->name }} — GoToEat</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&family=Inter:wght@400;500;600&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .glass-nav { backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }
        .cart-slide { transition: transform .3s cubic-bezier(.4,0,.2,1); }
        .no-scrollbar::-webkit-scrollbar { display:none; }
        .no-scrollbar { -ms-overflow-style:none; scrollbar-width:none; }
        [x-cloak] { display:none !important; }
    </style>
</head>
<body class="bg-background text-on-surface antialiased font-body-md">

@php
    $primary   = $restaurant->primary_color   ?? '#f97316';
    $secondary = $restaurant->secondary_color ?? '#006c49';
    $coverUrl  = $restaurant->cover_path  ? Storage::url($restaurant->cover_path)  : null;
    $logoUrl   = $restaurant->logo_path   ? Storage::url($restaurant->logo_path)   : null;
@endphp

{{-- Navbar ──────────────────────────────────────────────────── --}}
<header class="sticky top-0 z-40 glass-nav bg-surface/90 border-b border-outline-variant/20 shadow-sm">
    <div class="max-w-5xl mx-auto px-4 md:px-8 h-14 flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('explorar') }}"
               class="p-2 rounded-xl hover:bg-surface-container transition-colors text-on-surface-variant">
                <span class="material-symbols-outlined text-[22px]">arrow_back</span>
            </a>
            @if($logoUrl)
            <img src="{{ $logoUrl }}" class="w-8 h-8 rounded-lg object-contain" alt="logo"/>
            @endif
            <p class="font-bold text-on-surface truncate">{{ $restaurant->name }}</p>
        </div>
        <div class="flex items-center gap-2">
            @if($restaurant->avg_rating > 0)
            <div class="hidden sm:flex items-center gap-1">
                <span class="material-symbols-outlined text-[16px] text-amber-400" style="font-variation-settings:'FILL' 1">star</span>
                <span class="text-sm font-bold text-on-surface">{{ number_format($restaurant->avg_rating,1) }}</span>
                <span class="text-xs text-gray-400">({{ $restaurant->reviews_count }})</span>
            </div>
            @endif
            @if($restaurant->price_range)
            <span class="hidden sm:block text-xs font-bold text-gray-400 px-2 py-1 bg-surface-container rounded-full">
                {{ $restaurant->price_range }}
            </span>
            @endif
            <img src="{{ auth()->user()->avatarUrl() }}" class="w-8 h-8 rounded-full object-cover" alt=""/>
        </div>
    </div>
</header>

{{-- Flash --}}
@if(session('success'))
<div class="max-w-5xl mx-auto px-4 md:px-8 pt-3">
    <div class="p-3 bg-secondary/10 border border-secondary/20 rounded-xl flex items-center gap-2 text-sm text-secondary font-semibold">
        <span class="material-symbols-outlined text-[18px]" style="font-variation-settings:'FILL' 1">check_circle</span>
        {{ session('success') }}
    </div>
</div>
@endif

{{-- ══ MAIN con carrito Alpine.js ════════════════════════════ --}}
<div x-data="{
    cart: [],
    cartOpen: false,
    addItem(id, name, price, category) {
        const idx = this.cart.findIndex(i => i.id === id);
        if (idx >= 0) { this.cart[idx].qty++; }
        else { this.cart.push({ id, name, price, category, qty: 1 }); }
    },
    removeItem(id) {
        const idx = this.cart.findIndex(i => i.id === id);
        if (idx < 0) return;
        if (this.cart[idx].qty > 1) { this.cart[idx].qty--; }
        else { this.cart.splice(idx, 1); }
    },
    getQty(id) { const i = this.cart.find(i => i.id === id); return i ? i.qty : 0; },
    get total() { return this.cart.reduce((s,i) => s + i.price * i.qty, 0); },
    get count() { return this.cart.reduce((s,i) => s + i.qty, 0); },
    get cartJson() { return JSON.stringify(this.cart); },
    formatCOP(n) { return '\$' + Math.round(n).toLocaleString('es-CO'); }
}">

<div class="max-w-5xl mx-auto px-4 md:px-8 pb-32 md:pb-12">

    {{-- Hero ───────────────────────────────────────────────── --}}
    <div class="relative rounded-2xl overflow-hidden mb-8 mt-4"
         style="height: 220px; {{ $coverUrl ? 'background:url('.json_encode($coverUrl).') center/cover' : 'background-color:'.$primary.'20' }}">
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
        <div class="absolute bottom-5 left-5 text-white">
            <h1 class="font-black text-2xl md:text-3xl leading-tight">{{ $restaurant->name }}</h1>
            <div class="flex flex-wrap items-center gap-3 mt-1.5 text-white/80 text-sm">
                @if($restaurant->cuisine_type)
                <span class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">restaurant</span>
                    {{ $restaurant->cuisine_type }}
                </span>
                @endif
                @if($restaurant->address)
                <span class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">location_on</span>
                    {{ Str::limit($restaurant->address, 40) }}
                </span>
                @endif
                @if($restaurant->opening_hours)
                <span class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">schedule</span>
                    {{ $restaurant->opening_hours }}
                </span>
                @endif
            </div>
        </div>
        @if($logoUrl)
        <div class="absolute top-4 right-4">
            <img src="{{ $logoUrl }}" class="w-14 h-14 rounded-xl object-contain bg-white/90 p-1 shadow-lg" alt="logo"/>
        </div>
        @endif
    </div>

    {{-- Acciones rápidas ────────────────────────────────────── --}}
    <div class="flex gap-3 mb-8 flex-wrap">
        @if($restaurant->phone)
        <a href="tel:{{ $restaurant->phone }}"
           class="flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-on-surface hover:border-primary-container hover:text-primary-container transition-all">
            <span class="material-symbols-outlined text-[18px]">call</span>
            {{ $restaurant->phone }}
        </a>
        @endif
        @if($restaurant->whatsapp)
        <a href="https://wa.me/{{ preg_replace('/\D/','',$restaurant->whatsapp) }}" target="_blank"
           class="flex items-center gap-2 px-4 py-2.5 bg-emerald-50 border border-emerald-200 rounded-xl text-sm font-semibold text-emerald-700 hover:bg-emerald-100 transition-all">
            <span class="material-symbols-outlined text-[18px]">chat</span>
            WhatsApp
        </a>
        @endif
        @if($restaurant->website)
        <a href="{{ $restaurant->website }}" target="_blank"
           class="flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-semibold hover:border-primary-container hover:text-primary-container transition-all">
            <span class="material-symbols-outlined text-[18px]">language</span>
            Sitio web
        </a>
        @endif
        <a href="#resenas"
           class="flex items-center gap-2 px-4 py-2.5 bg-amber-50 border border-amber-200 rounded-xl text-sm font-semibold text-amber-700 hover:bg-amber-100 transition-all">
            <span class="material-symbols-outlined text-[18px]" style="font-variation-settings:'FILL' 1">star</span>
            {{ $restaurant->reviews_count }} reseñas
        </a>
    </div>

    {{-- Promociones / Destacados ─────────────────────────────── --}}
    @if($featured->isNotEmpty())
    <section class="mb-10">
        <h2 class="font-bold text-xl text-on-surface mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-[22px]" style="color:{{ $primary }};font-variation-settings:'FILL' 1">local_fire_department</span>
            Promociones del Día
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            @foreach($featured as $item)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden group hover:shadow-md transition-shadow">
                @if($item->image)
                <div class="h-36 overflow-hidden">
                    <img src="{{ Storage::url($item->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" alt="{{ $item->name }}"/>
                </div>
                @else
                <div class="h-24 flex items-center justify-center" style="background:{{ $primary }}15">
                    <span class="material-symbols-outlined text-4xl" style="color:{{ $primary }};font-variation-settings:'FILL' 1">restaurant_menu</span>
                </div>
                @endif
                <div class="p-4">
                    <div class="flex items-start justify-between gap-2 mb-1">
                        <div>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full text-white mb-1 inline-block" style="background:{{ $primary }}">PROMO</span>
                            <h3 class="font-bold text-sm text-on-surface">{{ $item->name }}</h3>
                        </div>
                        <p class="font-black text-base shrink-0" style="color:{{ $primary }}">${{ number_format($item->price, 0, ',', '.') }}</p>
                    </div>
                    @if($item->description)
                    <p class="text-xs text-gray-400 mb-3 line-clamp-2">{{ $item->description }}</p>
                    @endif
                    <div class="flex items-center gap-2">
                        <button @click="removeItem({{ $item->id }})" x-show="getQty({{ $item->id }}) > 0"
                                class="w-8 h-8 rounded-xl border font-bold flex items-center justify-center transition-all hover:opacity-80"
                                style="border-color:{{ $primary }};color:{{ $primary }}">−</button>
                        <span x-show="getQty({{ $item->id }}) > 0"
                              class="font-bold text-sm min-w-[20px] text-center" x-text="getQty({{ $item->id }})"></span>
                        <button @click="addItem({{ $item->id }}, '{{ addslashes($item->name) }}', {{ $item->price }}, '{{ addslashes($item->category ?? 'General') }}')"
                                class="flex-1 py-2 rounded-xl text-white font-bold text-sm transition-all active:scale-[0.97]"
                                style="background:{{ $primary }}">
                            <span x-text="getQty({{ $item->id }}) > 0 ? 'Agregar más' : 'Agregar'"></span>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Menú completo por categoría ─────────────────────────── --}}
    <section class="mb-10">
        <h2 class="font-bold text-xl text-on-surface mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-[22px]" style="font-variation-settings:'FILL' 1;color:{{ $primary }}">restaurant_menu</span>
            Menú
        </h2>

        @if($menuByCategory->isEmpty())
        <div class="py-12 text-center text-gray-400 bg-white rounded-2xl border border-gray-100">
            <span class="material-symbols-outlined text-4xl text-gray-200 block mb-2">no_meals</span>
            El restaurante aún no tiene ítems en su menú.
        </div>
        @else
        {{-- Categorías nav --}}
        <div class="flex gap-2 overflow-x-auto pb-2 mb-5 no-scrollbar">
            @foreach($menuByCategory->keys() as $cat)
            <a href="#cat-{{ Str::slug($cat) }}"
               class="px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap transition-all bg-surface-container text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high">
                {{ $cat ?? 'General' }}
            </a>
            @endforeach
        </div>

        <div class="space-y-8">
            @foreach($menuByCategory as $category => $items)
            <div id="cat-{{ Str::slug($category) }}">
                <h3 class="font-bold text-sm text-on-surface-variant uppercase tracking-widest mb-3 flex items-center gap-2">
                    <span class="w-8 h-0.5 rounded-full inline-block" style="background:{{ $primary }}"></span>
                    {{ $category ?? 'General' }}
                </h3>
                <div class="space-y-3">
                    @foreach($items as $item)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-4 hover:shadow-md transition-shadow">
                        @if($item->image)
                        <div class="w-20 h-20 rounded-xl overflow-hidden shrink-0">
                            <img src="{{ Storage::url($item->image) }}" class="w-full h-full object-cover" alt="{{ $item->name }}"/>
                        </div>
                        @else
                        <div class="w-20 h-20 rounded-xl shrink-0 flex items-center justify-center" style="background:{{ $primary }}10">
                            <span class="material-symbols-outlined text-3xl" style="color:{{ $primary }};font-variation-settings:'FILL' 0">restaurant_menu</span>
                        </div>
                        @endif

                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <h4 class="font-bold text-sm text-on-surface">{{ $item->name }}</h4>
                                    @if($item->is_featured)
                                    <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full text-white" style="background:{{ $primary }}">PROMO</span>
                                    @endif
                                </div>
                                <p class="font-black text-base shrink-0" style="color:{{ $primary }}">${{ number_format($item->price, 0, ',', '.') }}</p>
                            </div>
                            @if($item->description)
                            <p class="text-xs text-gray-400 mt-0.5 line-clamp-2">{{ $item->description }}</p>
                            @endif
                            @if($item->prep_time)
                            <p class="text-[11px] text-gray-300 mt-1 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[13px]">timer</span>
                                {{ $item->prep_time }} min
                            </p>
                            @endif
                        </div>

                        {{-- Controles de carrito --}}
                        <div class="flex items-center gap-2 shrink-0">
                            <button @click="removeItem({{ $item->id }})" x-show="getQty({{ $item->id }}) > 0"
                                    class="w-8 h-8 rounded-xl border font-bold flex items-center justify-center transition-all hover:opacity-80 active:scale-90"
                                    style="border-color:{{ $primary }};color:{{ $primary }}">−</button>
                            <span x-show="getQty({{ $item->id }}) > 0"
                                  class="font-bold text-sm min-w-[20px] text-center" x-text="getQty({{ $item->id }})"></span>
                            <button @click="addItem({{ $item->id }}, '{{ addslashes($item->name) }}', {{ $item->price }}, '{{ addslashes($category ?? 'General') }}')"
                                    class="w-9 h-9 rounded-xl text-white font-bold flex items-center justify-center transition-all hover:opacity-90 active:scale-90"
                                    style="background:{{ $primary }}">+</button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </section>

    {{-- Reseñas ──────────────────────────────────────────────── --}}
    <section id="resenas" class="mb-10">
        <h2 class="font-bold text-xl text-on-surface mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-[22px] text-amber-400" style="font-variation-settings:'FILL' 1">star</span>
            Reseñas y Calificaciones
        </h2>

        {{-- Resumen --}}
        @if($restaurant->avg_rating > 0)
        <div class="flex items-center gap-4 bg-amber-50 border border-amber-100 rounded-2xl p-5 mb-5">
            <div class="text-5xl font-black text-amber-500">{{ number_format($restaurant->avg_rating, 1) }}</div>
            <div>
                <div class="flex gap-0.5 mb-1">
                    @for($s = 1; $s <= 5; $s++)
                    <span class="material-symbols-outlined text-[22px] {{ $s <= round($restaurant->avg_rating) ? 'text-amber-400' : 'text-gray-200' }}"
                          style="font-variation-settings:'FILL' {{ $s <= round($restaurant->avg_rating) ? 1 : 0 }}">star</span>
                    @endfor
                </div>
                <p class="text-sm text-gray-500">Basado en {{ $restaurant->reviews_count }} reseñas</p>
            </div>
        </div>
        @endif

        {{-- Escribir reseña --}}
        @auth
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5">
            <h3 class="font-bold text-sm text-on-surface mb-3">
                {{ $userReview ? 'Tu reseña (editar)' : 'Comparte tu experiencia' }}
            </h3>
            <form method="POST" action="{{ route('restaurante.review', $restaurant) }}" class="space-y-3">
                @csrf
                {{-- Estrellas --}}
                <div class="flex items-center gap-3">
                    <span class="text-xs text-gray-400 font-semibold">Calificación:</span>
                    <div class="flex gap-1" id="starRating">
                        @for($s = 1; $s <= 5; $s++)
                        <button type="button" onclick="setStar({{ $s }})" data-star="{{ $s }}"
                                class="text-[28px] transition-colors {{ $userReview && $userReview->rating >= $s ? 'text-amber-400' : 'text-gray-200' }}"
                                style="font-variation-settings:'FILL' {{ ($userReview && $userReview->rating >= $s) ? 1 : 0 }};line-height:1">
                            <span class="material-symbols-outlined text-[28px]">star</span>
                        </button>
                        @endfor
                    </div>
                    <input id="ratingVal" name="rating" type="hidden" value="{{ $userReview?->rating ?? '' }}" required/>
                </div>
                <input name="title" type="text" placeholder="Título de tu reseña" maxlength="120"
                       value="{{ $userReview?->title }}"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-300/30 focus:border-amber-400 outline-none transition-all"/>
                <textarea name="body" rows="3" placeholder="Cuéntanos más sobre tu experiencia..." maxlength="1000"
                          class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-300/30 focus:border-amber-400 outline-none transition-all resize-none">{{ $userReview?->body }}</textarea>
                <button type="submit"
                        class="px-6 py-2.5 text-white rounded-xl font-bold text-sm transition-all active:scale-[0.97] hover:opacity-90"
                        style="background:{{ $primary }}">
                    {{ $userReview ? 'Actualizar reseña' : 'Publicar reseña' }}
                </button>
            </form>
        </div>
        @endauth

        {{-- Lista de reseñas --}}
        @if($reviews->isEmpty())
        <div class="py-10 text-center text-gray-400 bg-white rounded-2xl border border-gray-100">
            <span class="material-symbols-outlined text-4xl text-gray-200 block mb-2">rate_review</span>
            Sé el primero en dejar una reseña.
        </div>
        @else
        <div class="space-y-4">
            @foreach($reviews as $review)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <div class="flex items-start justify-between gap-3 mb-2">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-primary-container/20 flex items-center justify-center font-bold text-sm text-primary-container shrink-0">
                            {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-sm text-on-surface">{{ $review->user->name ?? 'Usuario' }}</p>
                            <div class="flex gap-0.5">
                                @for($s = 1; $s <= 5; $s++)
                                <span class="material-symbols-outlined text-[14px] {{ $s <= $review->rating ? 'text-amber-400' : 'text-gray-200' }}"
                                      style="font-variation-settings:'FILL' {{ $s <= $review->rating ? 1 : 0 }}">star</span>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 shrink-0">{{ $review->created_at->locale('es')->diffForHumans() }}</p>
                </div>
                @if($review->title)
                <p class="font-semibold text-sm text-on-surface mb-1">{{ $review->title }}</p>
                @endif
                @if($review->body)
                <p class="text-sm text-gray-600">{{ $review->body }}</p>
                @endif
            </div>
            @endforeach
        </div>
        @endif
    </section>
</div>

{{-- ══ CARRITO FLOTANTE ════════════════════════════════════════ --}}

{{-- Badge del carrito (mobile) --}}
<div x-show="count > 0" x-cloak
     class="fixed bottom-5 left-1/2 -translate-x-1/2 z-50 md:hidden">
    <button @click="cartOpen = true"
            class="flex items-center gap-3 px-5 py-3 text-white rounded-2xl shadow-2xl font-bold text-sm"
            style="background:{{ $primary }}">
        <span class="material-symbols-outlined text-[20px]" style="font-variation-settings:'FILL' 1">shopping_cart</span>
        <span x-text="count + ' ítem' + (count > 1 ? 's' : '')"></span>
        <span class="font-black" x-text="formatCOP(total)"></span>
    </button>
</div>

{{-- Panel carrito lateral ──────────────────────────────────── --}}
<div x-show="cartOpen" x-cloak
     class="fixed inset-0 z-[60] flex"
     @click.self="cartOpen = false">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="cartOpen = false"></div>
    <div class="ml-auto w-full max-w-sm bg-white h-full shadow-2xl flex flex-col relative z-10 cart-slide"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full">

        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between shrink-0">
            <h3 class="font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px]" style="color:{{ $primary }};font-variation-settings:'FILL' 1">shopping_cart</span>
                Mi Pedido
            </h3>
            <button @click="cartOpen = false" class="p-1.5 rounded-xl hover:bg-gray-100 transition-colors">
                <span class="material-symbols-outlined text-[20px] text-gray-400">close</span>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto px-5 py-4">
            <template x-if="cart.length === 0">
                <div class="flex flex-col items-center justify-center h-40 text-gray-400 text-sm text-center">
                    <span class="material-symbols-outlined text-4xl text-gray-200 mb-2">shopping_cart</span>
                    <p>Tu pedido está vacío.</p>
                    <p class="text-xs mt-1">Agrega ítems del menú.</p>
                </div>
            </template>
            <template x-if="cart.length > 0">
                <div class="space-y-3">
                    <template x-for="item in cart" :key="item.id">
                        <div class="flex items-center gap-3 bg-gray-50 rounded-xl p-3">
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-sm text-on-surface truncate" x-text="item.name"></p>
                                <p class="text-xs text-gray-400" x-text="item.category"></p>
                                <p class="text-xs font-bold mt-0.5" style="color:{{ $primary }}" x-text="formatCOP(item.price)"></p>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <button @click="removeItem(item.id)"
                                        class="w-7 h-7 rounded-lg border font-bold flex items-center justify-center text-sm transition-all"
                                        style="border-color:{{ $primary }};color:{{ $primary }}">−</button>
                                <span class="font-bold text-sm min-w-[20px] text-center" x-text="item.qty"></span>
                                <button @click="addItem(item.id, item.name, item.price, item.category)"
                                        class="w-7 h-7 rounded-lg text-white font-bold flex items-center justify-center text-sm transition-all"
                                        style="background:{{ $primary }}">+</button>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
        </div>

        {{-- Resumen + Reservar --}}
        <template x-if="cart.length > 0">
            <div class="px-5 py-5 border-t border-gray-100 shrink-0 space-y-4">
                <div class="flex justify-between items-center">
                    <span class="font-semibold text-on-surface-variant text-sm">Total estimado</span>
                    <span class="font-black text-lg" style="color:{{ $primary }}" x-text="formatCOP(total)"></span>
                </div>
                <p class="text-xs text-gray-400 -mt-2">El total final puede variar según disponibilidad.</p>
                <button @click="cartOpen = false; document.getElementById('modalReservaCart').classList.remove('hidden')"
                        class="w-full py-3.5 text-white rounded-xl font-black text-sm transition-all active:scale-[0.97] hover:opacity-90 shadow-lg flex items-center justify-center gap-2"
                        style="background:{{ $primary }}">
                    <span class="material-symbols-outlined text-[20px]" style="font-variation-settings:'FILL' 1">event_available</span>
                    Confirmar Reserva
                </button>
                <button @click="cart = []; cartOpen = false"
                        class="w-full py-2.5 border border-gray-200 text-gray-500 rounded-xl font-semibold text-sm hover:bg-gray-50 transition-colors">
                    Limpiar pedido
                </button>
            </div>
        </template>
    </div>
</div>

{{-- Botón carrito desktop --}}
<div x-show="count > 0" x-cloak
     class="fixed bottom-8 right-8 z-50 hidden md:block">
    <button @click="cartOpen = true"
            class="flex items-center gap-3 px-5 py-3.5 text-white rounded-2xl shadow-2xl font-bold text-sm transition-all hover:opacity-90 active:scale-[0.97]"
            style="background:{{ $primary }}">
        <span class="material-symbols-outlined text-[20px]" style="font-variation-settings:'FILL' 1">shopping_cart</span>
        <span x-text="count + ' ítem' + (count > 1 ? 's' : '')"></span>
        <span class="font-black" x-text="formatCOP(total)"></span>
    </button>
</div>

</div>{{-- /x-data --}}

{{-- ══ MODAL: Reserva con carrito ═════════════════════════════ --}}
<div id="modalReservaCart"
     class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-[60] flex items-center justify-center p-4"
     onclick="if(event.target===this)this.classList.add('hidden')">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg flex flex-col" style="max-height:90vh;">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between shrink-0">
            <h3 class="font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px] text-primary-container">calendar_add_on</span>
                Confirmar Reserva
            </h3>
            <button onclick="document.getElementById('modalReservaCart').classList.add('hidden')"
                    class="p-1.5 rounded-lg hover:bg-gray-100 transition-colors">
                <span class="material-symbols-outlined text-[20px] text-gray-400">close</span>
            </button>
        </div>
        <form method="POST" action="{{ route('reservas.store') }}" class="flex flex-col flex-1 overflow-hidden" id="formReservaCart">
            @csrf
            <input type="hidden" name="restaurant_id" value="{{ $restaurant->id }}">
            <input id="cartDataInput" name="selected_items" type="hidden" value="[]">
            <div class="overflow-y-auto flex-1 px-6 py-5 space-y-4">
                {{-- Resumen del pedido --}}
                <div class="bg-orange-50 rounded-xl p-4 border border-orange-100">
                    <p class="text-xs font-bold uppercase tracking-wide text-gray-400 mb-2">Tu pedido — {{ $restaurant->name }}</p>
                    <div id="cartSummaryModal" class="space-y-1 text-sm"></div>
                    <div class="border-t border-orange-200 mt-2 pt-2 flex justify-between font-black" id="cartTotalModal"></div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5">Fecha *</label>
                        <input name="reservation_date" type="date" required
                               min="{{ now()->toDateString() }}"
                               value="{{ old('reservation_date', now()->addDay()->toDateString()) }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all"/>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5">Hora *</label>
                        <select name="reservation_time" required
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all">
                            @foreach(['12:00','12:30','13:00','13:30','14:00','14:30','18:00','18:30','19:00','19:30','20:00','20:30','21:00','21:30','22:00'] as $h)
                            <option value="{{ $h }}">{{ $h }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5">Número de personas *</label>
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="var i=document.getElementById('psCart');i.value=Math.max(1,parseInt(i.value||2)-1)"
                                class="w-10 h-10 rounded-xl bg-gray-100 font-bold text-lg hover:bg-gray-200 active:scale-90 transition-all">−</button>
                        <input id="psCart" name="party_size" type="number" min="1" max="20" value="2" required
                               class="flex-1 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-center font-bold focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none"/>
                        <button type="button" onclick="var i=document.getElementById('psCart');i.value=Math.min(20,parseInt(i.value||2)+1)"
                                class="w-10 h-10 rounded-xl bg-gray-100 font-bold text-lg hover:bg-gray-200 active:scale-90 transition-all">+</button>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5">Notas adicionales</label>
                    <textarea name="notes" rows="2" placeholder="Alergias, ocasión especial, preferencias..."
                              class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all resize-none"></textarea>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 flex gap-3 shrink-0">
                <button type="button" onclick="document.getElementById('modalReservaCart').classList.add('hidden')"
                        class="flex-1 py-2.5 border border-gray-200 rounded-xl text-sm font-semibold text-gray-500 hover:bg-gray-50 transition-colors">
                    Cancelar
                </button>
                <button type="submit" id="btnConfirmReserva"
                        class="flex-1 py-2.5 text-white rounded-xl text-sm font-black hover:opacity-90 active:scale-[0.97] transition-all shadow-sm"
                        style="background:{{ $primary }}">
                    Confirmar Reserva
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// ── Estrellas de reseña ────────────────────────────────────────
function setStar(val) {
    document.getElementById('ratingVal').value = val;
    document.querySelectorAll('#starRating button').forEach((btn, idx) => {
        const filled = idx < val;
        btn.style.fontVariationSettings = filled ? "'FILL' 1" : "'FILL' 0";
        btn.className = btn.className.replace(/text-amber-400|text-gray-200/g, '');
        btn.className += ' ' + (filled ? 'text-amber-400' : 'text-gray-200');
    });
}

// ── Sincronizar carrito con el modal de reserva ───────────────
// Escucha el clic en "Confirmar Reserva" para inyectar el carrito
document.addEventListener('click', function(e) {
    if (e.target.closest('#formReservaCart button[type=submit]')) {
        // Obtener datos del carrito de Alpine.js
        const alpineEl = document.querySelector('[x-data]');
        if (alpineEl && alpineEl._x_dataStack) {
            // Alpine.js v3
            try {
                const data = Alpine.evaluate(alpineEl, 'cart');
                document.getElementById('cartDataInput').value = JSON.stringify(data);
            } catch(err) {}
        }
    }
});

// Actualizar resumen del modal cuando se abre
const cartModal = document.getElementById('modalReservaCart');
const observer = new MutationObserver(() => {
    if (!cartModal.classList.contains('hidden')) {
        const alpineEl = document.querySelector('[x-data]');
        if (!alpineEl) return;
        try {
            const cart  = Alpine.evaluate(alpineEl, 'cart');
            const total = Alpine.evaluate(alpineEl, 'total');
            document.getElementById('cartDataInput').value = JSON.stringify(cart);
            const summaryEl = document.getElementById('cartSummaryModal');
            const totalEl   = document.getElementById('cartTotalModal');
            if (cart.length === 0) {
                summaryEl.innerHTML = '<p class="text-gray-400 text-xs">Sin ítems en el pedido.</p>';
                totalEl.innerHTML = '';
            } else {
                summaryEl.innerHTML = cart.map(i =>
                    '<div class="flex justify-between"><span>' + i.name + ' ×' + i.qty + '</span>' +
                    '<span class="font-bold">$' + Math.round(i.price * i.qty).toLocaleString('es-CO') + '</span></div>'
                ).join('');
                totalEl.innerHTML = '<span>Total estimado</span><span style="color:{{ $primary }}">$' + Math.round(total).toLocaleString('es-CO') + '</span>';
            }
        } catch(err) {}
    }
});
if (cartModal) observer.observe(cartModal, { attributes: true, attributeFilter: ['class'] });
</script>

</body>
</html>
