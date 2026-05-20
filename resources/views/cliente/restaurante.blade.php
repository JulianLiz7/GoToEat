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
        body { background: #f0f2f7; }
        .hero-cover { background-size: cover; background-position: center; }
        .category-tab.active { background: #f97316; color: white; }
        .category-tab { background: white; color: #6b7280; border: 1px solid #e5e7eb; transition: all .2s; }
        .category-tab:hover { border-color: #f97316; color: #f97316; }
        .menu-card { transition: transform .2s, box-shadow .2s; }
        .menu-card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px -4px rgba(0,0,0,.12); }
        .cart-bump { animation: bump .25s cubic-bezier(.34,1.56,.64,1); }
        @keyframes bump { 0%,100% { transform: scale(1); } 50% { transform: scale(1.15); } }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-body antialiased"
      x-data="{
        cart: [],
        activeCategory: '',
        cartOpen: false,
        reservaModal: false,
        reviewModal: false,
        cartBump: false,
        addToCart(item) {
            const idx = this.cart.findIndex(c => c.id === item.id);
            if (idx >= 0) {
                this.cart[idx].qty++;
            } else {
                this.cart.push({ id: item.id, name: item.name, price: item.price, qty: 1 });
            }
            this.cartBump = true; setTimeout(() => this.cartBump = false, 300);
        },
        removeFromCart(id) {
            const idx = this.cart.findIndex(c => c.id === id);
            if (idx >= 0) {
                if (this.cart[idx].qty > 1) this.cart[idx].qty--;
                else this.cart.splice(idx, 1);
            }
        },
        qtyOf(id) { const i = this.cart.find(c => c.id === id); return i ? i.qty : 0; },
        get cartCount() { return this.cart.reduce((s, i) => s + i.qty, 0); },
        get cartTotal() { return this.cart.reduce((s, i) => s + (i.price * i.qty), 0); },
      }">

{{-- ══ HERO ─────────────────────────────────────────────────────── --}}
<div class="relative h-72 md:h-96 hero-cover"
     style="background-image: url('{{ $restaurant->cover_path ? Storage::url($restaurant->cover_path) : 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=1200' }}')">
    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-black/20"></div>

    {{-- Back button --}}
    <div class="absolute top-4 left-4 right-4 flex items-center justify-between">
        <a href="{{ route('explorar') }}"
           class="flex items-center gap-2 bg-white/20 backdrop-blur hover:bg-white/30 text-white px-4 py-2 rounded-full text-sm font-medium transition-all">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            Explorar
        </a>
        @if($restaurant->whatsapp)
        <a href="https://wa.me/{{ preg_replace('/\D/','',$restaurant->whatsapp) }}"
           target="_blank"
           class="flex items-center gap-2 bg-green-500/80 backdrop-blur hover:bg-green-500 text-white px-4 py-2 rounded-full text-sm font-medium transition-all">
            <span class="material-symbols-outlined text-[18px]">chat</span>
            WhatsApp
        </a>
        @endif
    </div>

    {{-- Restaurant info overlay --}}
    <div class="absolute bottom-0 left-0 right-0 p-6 md:p-8">
        <div class="flex items-end gap-4">
            @if($restaurant->logo_path)
            <img src="{{ Storage::url($restaurant->logo_path) }}" class="w-16 h-16 md:w-20 md:h-20 rounded-2xl object-cover border-2 border-white/40 shadow-xl flex-shrink-0">
            @else
            <div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl flex items-center justify-center text-white font-bold text-xl border-2 border-white/40 flex-shrink-0"
                 style="background: {{ $restaurant->primary_color ?? '#f97316' }}">
                {{ strtoupper(substr($restaurant->name, 0, 2)) }}
            </div>
            @endif
            <div class="flex-1">
                <div class="flex flex-wrap items-center gap-2 mb-1">
                    @if($restaurant->cuisine_type)
                    <span class="bg-white/20 backdrop-blur text-white text-xs px-3 py-1 rounded-full">{{ $restaurant->cuisine_type }}</span>
                    @endif
                    @if($restaurant->price_range)
                    <span class="bg-white/20 backdrop-blur text-white text-xs px-3 py-1 rounded-full">{{ $restaurant->price_range }}</span>
                    @endif
                </div>
                <h1 class="font-heading text-2xl md:text-3xl font-bold text-white">{{ $restaurant->name }}</h1>
                @if($restaurant->avg_rating)
                <div class="flex items-center gap-2 mt-1">
                    <div class="flex">
                        @for($i=1; $i<=5; $i++)
                        <span class="material-symbols-outlined text-[16px] {{ $i <= round($restaurant->avg_rating) ? 'text-yellow-400' : 'text-white/30' }}" style="font-variation-settings:'FILL' 1">star</span>
                        @endfor
                    </div>
                    <span class="text-white/80 text-sm">{{ number_format($restaurant->avg_rating, 1) }}
                        @if($restaurant->reviews_count) ({{ $restaurant->reviews_count }} reseñas) @endif
                    </span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ══ MAIN CONTENT ──────────────────────────────────────────────── --}}
<div class="max-w-7xl mx-auto px-4 md:px-6 py-8">
    <div class="flex gap-8">

        {{-- ── LEFT: Menu ──────────────────────────────────────────── --}}
        <div class="flex-1 min-w-0 space-y-8">

            {{-- About --}}
            @if($restaurant->description)
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                <p class="text-gray-600 text-sm leading-relaxed">{{ $restaurant->description }}</p>
                @if($restaurant->opening_hours || $restaurant->website)
                <div class="flex flex-wrap gap-4 mt-3 text-xs text-gray-500">
                    @if($restaurant->opening_hours)
                    <span class="flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[14px]">schedule</span>
                        {{ $restaurant->opening_hours }}
                    </span>
                    @endif
                    @if($restaurant->website)
                    <a href="{{ $restaurant->website }}" target="_blank" class="flex items-center gap-1.5 text-primary hover:underline">
                        <span class="material-symbols-outlined text-[14px]">language</span>
                        Sitio web
                    </a>
                    @endif
                </div>
                @endif
            </div>
            @endif

            {{-- Promoted / Featured items --}}
            @if($featured->isNotEmpty())
            <section>
                <div class="flex items-center gap-2 mb-4">
                    <span class="material-symbols-outlined text-primary text-[22px]" style="font-variation-settings:'FILL' 1">local_fire_department</span>
                    <h2 class="font-heading text-lg font-bold text-gray-900">Destacados</h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($featured as $item)
                    <div class="menu-card bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm">
                        @if($item->image)
                        <div class="h-36 bg-gray-100 overflow-hidden">
                            <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                        </div>
                        @else
                        <div class="h-36 bg-gradient-to-br from-orange-50 to-orange-100 flex items-center justify-center">
                            <span class="material-symbols-outlined text-orange-200 text-[48px]">restaurant_menu</span>
                        </div>
                        @endif
                        <div class="p-4">
                            <div class="flex items-start justify-between gap-2 mb-1">
                                <h3 class="font-semibold text-gray-900 text-sm leading-tight">{{ $item->name }}</h3>
                                <span class="text-primary font-bold text-sm flex-shrink-0">${{ number_format($item->price, 0, ',', '.') }}</span>
                            </div>
                            @if($item->description)
                            <p class="text-xs text-gray-500 line-clamp-2 mb-3">{{ $item->description }}</p>
                            @endif
                            <div class="flex items-center gap-2" x-data>
                                <template x-if="$root.qtyOf({{ $item->id }}) > 0">
                                    <div class="flex items-center gap-2 flex-1">
                                        <button @click="$root.removeFromCart({{ $item->id }})"
                                                class="w-8 h-8 bg-gray-100 hover:bg-gray-200 rounded-lg flex items-center justify-center font-bold text-gray-600 transition-all">−</button>
                                        <span class="font-bold text-gray-800 w-5 text-center text-sm" x-text="$root.qtyOf({{ $item->id }})"></span>
                                        <button @click="$root.addToCart({ id: {{ $item->id }}, name: '{{ addslashes($item->name) }}', price: {{ $item->price }} })"
                                                class="w-8 h-8 bg-primary hover:bg-orange-600 rounded-lg flex items-center justify-center font-bold text-white transition-all">+</button>
                                    </div>
                                </template>
                                <template x-if="$root.qtyOf({{ $item->id }}) === 0">
                                    <button @click="$root.addToCart({ id: {{ $item->id }}, name: '{{ addslashes($item->name) }}', price: {{ $item->price }} })"
                                            class="flex-1 bg-primary hover:bg-orange-600 text-white text-xs font-semibold py-2 rounded-xl transition-all">
                                        Agregar
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
            @endif

            {{-- Full Menu by Category --}}
            @if($menuByCategory->isNotEmpty())
            <section id="menu">
                <div class="flex items-center gap-2 mb-4">
                    <span class="material-symbols-outlined text-gray-600 text-[22px]" style="font-variation-settings:'FILL' 1">menu_book</span>
                    <h2 class="font-heading text-lg font-bold text-gray-900">Menú Completo</h2>
                </div>

                {{-- Category tabs --}}
                <div class="flex gap-2 overflow-x-auto no-scrollbar pb-2 mb-5">
                    <button @click="activeCategory = ''"
                            :class="activeCategory === '' ? 'bg-primary text-white border-primary' : 'bg-white text-gray-500 border-gray-200 hover:border-primary hover:text-primary'"
                            class="flex-shrink-0 px-4 py-2 rounded-full text-sm font-semibold border transition-all">
                        Todos
                    </button>
                    @foreach($menuByCategory as $category => $items)
                    <button @click="activeCategory = '{{ $category }}'"
                            :class="activeCategory === '{{ $category }}' ? 'bg-primary text-white border-primary' : 'bg-white text-gray-500 border-gray-200 hover:border-primary hover:text-primary'"
                            class="flex-shrink-0 px-4 py-2 rounded-full text-sm font-semibold border transition-all">
                        {{ $category }}
                    </button>
                    @endforeach
                </div>

                {{-- Menu items --}}
                @foreach($menuByCategory as $category => $items)
                <div x-show="activeCategory === '' || activeCategory === '{{ $category }}'" class="mb-8">
                    <h3 class="font-heading text-base font-bold text-gray-600 uppercase tracking-wider mb-3">{{ $category }}</h3>
                    <div class="space-y-3">
                        @foreach($items as $item)
                        <div class="menu-card bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex gap-4 items-center">
                            @if($item->image)
                            <div class="w-16 h-16 rounded-xl overflow-hidden flex-shrink-0">
                                <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                            </div>
                            @else
                            <div class="w-16 h-16 rounded-xl bg-orange-50 flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-orange-200 text-[28px]">restaurant_menu</span>
                            </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2">
                                    <h4 class="font-semibold text-gray-900 text-sm">{{ $item->name }}</h4>
                                    <span class="text-primary font-bold text-sm flex-shrink-0">${{ number_format($item->price, 0, ',', '.') }}</span>
                                </div>
                                @if($item->description)
                                <p class="text-xs text-gray-500 mt-0.5 line-clamp-1">{{ $item->description }}</p>
                                @endif
                            </div>
                            <div class="flex-shrink-0" x-data>
                                <template x-if="$root.qtyOf({{ $item->id }}) > 0">
                                    <div class="flex items-center gap-1.5">
                                        <button @click="$root.removeFromCart({{ $item->id }})"
                                                class="w-7 h-7 bg-gray-100 hover:bg-gray-200 rounded-lg flex items-center justify-center font-bold text-gray-600 text-sm transition-all">−</button>
                                        <span class="w-4 text-center font-bold text-gray-800 text-sm" x-text="$root.qtyOf({{ $item->id }})"></span>
                                        <button @click="$root.addToCart({ id: {{ $item->id }}, name: '{{ addslashes($item->name) }}', price: {{ $item->price }} })"
                                                class="w-7 h-7 bg-primary hover:bg-orange-600 rounded-lg flex items-center justify-center font-bold text-white text-sm transition-all">+</button>
                                    </div>
                                </template>
                                <template x-if="$root.qtyOf({{ $item->id }}) === 0">
                                    <button @click="$root.addToCart({ id: {{ $item->id }}, name: '{{ addslashes($item->name) }}', price: {{ $item->price }} })"
                                            class="w-7 h-7 bg-orange-50 hover:bg-primary hover:text-white rounded-lg flex items-center justify-center text-primary text-lg font-bold transition-all">+</button>
                                </template>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </section>
            @endif

            {{-- Reviews --}}
            <section id="resenas">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-yellow-400 text-[22px]" style="font-variation-settings:'FILL' 1">star</span>
                        <h2 class="font-heading text-lg font-bold text-gray-900">Reseñas</h2>
                    </div>
                    <button @click="reviewModal = true"
                            class="text-sm font-semibold text-primary hover:underline flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">rate_review</span>
                        {{ $userReview ? 'Editar reseña' : 'Escribir reseña' }}
                    </button>
                </div>

                @if($reviews->isEmpty())
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 text-center">
                    <span class="material-symbols-outlined text-gray-300 text-[40px] mb-2 block">rate_review</span>
                    <p class="text-sm text-gray-500">Sé el primero en dejar una reseña</p>
                </div>
                @else
                <div class="space-y-3">
                    @foreach($reviews as $review)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-primary to-orange-600 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                {{ strtoupper(substr($review->user->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <p class="font-semibold text-gray-900 text-sm">{{ $review->user->name }}</p>
                                    <div class="flex">
                                        @for($i=1; $i<=5; $i++)
                                        <span class="material-symbols-outlined text-[14px] {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-200' }}" style="font-variation-settings:'FILL' 1">star</span>
                                        @endfor
                                    </div>
                                </div>
                                @if($review->title)
                                <p class="font-medium text-gray-700 text-sm mt-0.5">{{ $review->title }}</p>
                                @endif
                                @if($review->body)
                                <p class="text-gray-500 text-sm mt-1">{{ $review->body }}</p>
                                @endif
                                <p class="text-xs text-gray-400 mt-2">{{ $review->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </section>
        </div>

        {{-- ── RIGHT: Booking Widget ───────────────────────────────── --}}
        <div id="reservar" class="hidden lg:block w-80 flex-shrink-0">
            <div class="sticky top-6 space-y-4">

                {{-- Cart Summary --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden" x-show="cart.length > 0" x-cloak>
                    <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-[20px]" style="font-variation-settings:'FILL' 1">shopping_bag</span>
                            <span class="font-semibold text-gray-900 text-sm">Mi Selección</span>
                        </div>
                        <span class="text-xs text-gray-500" x-text="cartCount + ' ítem' + (cartCount !== 1 ? 's' : '')"></span>
                    </div>
                    <div class="p-4 space-y-2 max-h-48 overflow-y-auto">
                        <template x-for="item in cart" :key="item.id">
                            <div class="flex items-center gap-2 text-sm">
                                <span class="flex-1 text-gray-700 truncate" x-text="item.name"></span>
                                <span class="text-gray-400 text-xs" x-text="'×' + item.qty"></span>
                                <span class="text-primary font-semibold" x-text="'$' + (item.price * item.qty).toLocaleString('es-CO')"></span>
                            </div>
                        </template>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 flex items-center justify-between">
                        <span class="text-sm font-semibold text-gray-700">Total estimado</span>
                        <span class="font-bold text-primary" x-text="'$' + cartTotal.toLocaleString('es-CO')"></span>
                    </div>
                </div>

                {{-- Reserve form --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-gray-100">
                        <h3 class="font-heading text-base font-bold text-gray-900">Hacer Reserva</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Confirma tu visita a {{ $restaurant->name }}</p>
                    </div>
                    <form method="POST" action="{{ route('reservas.store') }}" class="p-4 space-y-3"
                          @submit.prevent="
                            $el.querySelectorAll('.cart-item-input').forEach(e => e.remove());
                            cart.forEach((item, i) => {
                                ['id','name','price','qty'].forEach(k => {
                                    const inp = document.createElement('input');
                                    inp.type = 'hidden';
                                    inp.name = 'selected_items[' + i + '][' + k + ']';
                                    inp.value = item[k];
                                    inp.className = 'cart-item-input';
                                    $el.appendChild(inp);
                                });
                            });
                            $el.submit();
                          ">
                        @csrf
                        <input type="hidden" name="restaurant_id" value="{{ $restaurant->id }}">

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Fecha</label>
                            <input type="date" name="reservation_date" required
                                   min="{{ now()->format('Y-m-d') }}"
                                   class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary bg-gray-50 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Hora</label>
                            <input type="time" name="reservation_time" required
                                   class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary bg-gray-50 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Personas</label>
                            <div class="flex gap-1.5 flex-wrap">
                                @foreach(range(1,8) as $n)
                                <label class="cursor-pointer">
                                    <input type="radio" name="party_size" value="{{ $n }}" class="sr-only peer" {{ $n===2 ? 'checked' : '' }}>
                                    <span class="w-9 h-9 rounded-xl border-2 border-gray-200 flex items-center justify-center text-xs font-bold text-gray-600 peer-checked:border-primary peer-checked:bg-primary peer-checked:text-white transition-all block">{{ $n }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Notas</label>
                            <textarea name="notes" rows="2" placeholder="Alergias, ocasión especial…"
                                      class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary bg-gray-50 resize-none transition-all"></textarea>
                        </div>
                        <button type="submit"
                                class="w-full bg-primary hover:bg-orange-600 text-white font-bold py-3 rounded-xl transition-all shadow-md shadow-orange-200 active:scale-95 text-sm flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[18px]" style="font-variation-settings:'FILL' 1">event_available</span>
                            Solicitar Reserva
                        </button>
                        <p class="text-xs text-gray-400 text-center">Recibirás un QR de confirmación</p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══ MOBILE CART FAB ─────────────────────────────────────────── --}}
<div class="fixed bottom-6 right-4 z-40 lg:hidden" x-show="cart.length > 0" x-cloak>
    <button @click="cartOpen = true"
            class="flex items-center gap-2 bg-primary hover:bg-orange-600 text-white px-5 py-3.5 rounded-2xl shadow-xl shadow-orange-200 font-semibold text-sm transition-all active:scale-95"
            :class="cartBump ? 'cart-bump' : ''">
        <span class="material-symbols-outlined text-[20px]" style="font-variation-settings:'FILL' 1">shopping_bag</span>
        <span x-text="cartCount + ' ítem' + (cartCount !== 1 ? 's' : '')"></span>
        <span class="bg-white/30 rounded-lg px-2 py-0.5 text-xs font-bold" x-text="'$' + cartTotal.toLocaleString('es-CO')"></span>
    </button>
</div>

{{-- ══ MOBILE CART DRAWER ───────────────────────────────────────── --}}
<div x-show="cartOpen" x-cloak class="fixed inset-0 z-50 flex items-end">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="cartOpen = false"></div>
    <div class="relative bg-white w-full rounded-t-3xl shadow-2xl z-10 max-h-[85vh] flex flex-col">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="font-heading text-base font-bold text-gray-900">Mi Selección</h3>
                <p class="text-xs text-gray-500" x-text="cartCount + ' ítem' + (cartCount !== 1 ? 's' : '')"></p>
            </div>
            <button @click="cartOpen = false" class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                <span class="material-symbols-outlined text-gray-500 text-[18px]">close</span>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto p-5 space-y-3">
            <template x-for="item in cart" :key="item.id">
                <div class="flex items-center gap-3 bg-gray-50 rounded-xl p-3">
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800 text-sm truncate" x-text="item.name"></p>
                        <p class="text-xs text-primary font-bold" x-text="'$' + (item.price * item.qty).toLocaleString('es-CO')"></p>
                    </div>
                    <div class="flex items-center gap-1.5 flex-shrink-0">
                        <button @click="removeFromCart(item.id)"
                                class="w-7 h-7 bg-white border border-gray-200 rounded-lg flex items-center justify-center font-bold text-gray-600 text-sm transition-all">−</button>
                        <span class="w-5 text-center font-bold text-gray-800 text-sm" x-text="item.qty"></span>
                        <button @click="addToCart(item)"
                                class="w-7 h-7 bg-primary rounded-lg flex items-center justify-center font-bold text-white text-sm transition-all">+</button>
                    </div>
                </div>
            </template>
        </div>
        <div class="p-5 border-t border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <span class="font-semibold text-gray-700">Total estimado</span>
                <span class="text-lg font-bold text-primary" x-text="'$' + cartTotal.toLocaleString('es-CO')"></span>
            </div>
            <button @click="cartOpen = false; reservaModal = true"
                    class="w-full bg-primary hover:bg-orange-600 text-white font-bold py-3.5 rounded-xl transition-all shadow-md shadow-orange-200 active:scale-95 text-sm">
                Continuar con la reserva
            </button>
        </div>
    </div>
</div>

{{-- ══ MOBILE RESERVE MODAL ─────────────────────────────────────── --}}
<div x-show="reservaModal" x-cloak class="fixed inset-0 z-50 flex items-end sm:items-center justify-center">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="reservaModal = false"></div>
    <div class="relative bg-white w-full max-w-lg rounded-t-3xl sm:rounded-3xl shadow-2xl z-10 max-h-[90vh] overflow-y-auto">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="font-heading text-base font-bold text-gray-900">Confirmar Reserva</h3>
                <p class="text-xs text-gray-500">{{ $restaurant->name }}</p>
            </div>
            <button @click="reservaModal = false" class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                <span class="material-symbols-outlined text-gray-500 text-[18px]">close</span>
            </button>
        </div>
        <form method="POST" action="{{ route('reservas.store') }}" class="p-5 space-y-4"
              @submit.prevent="
                $el.querySelectorAll('.cart-item-input').forEach(e => e.remove());
                cart.forEach((item, i) => {
                    ['id','name','price','qty'].forEach(k => {
                        const inp = document.createElement('input');
                        inp.type = 'hidden';
                        inp.name = 'selected_items[' + i + '][' + k + ']';
                        inp.value = item[k];
                        inp.className = 'cart-item-input';
                        $el.appendChild(inp);
                    });
                });
                $el.submit();
              ">
            @csrf
            <input type="hidden" name="restaurant_id" value="{{ $restaurant->id }}">

            @if($cart ?? false)
            <div class="bg-orange-50 rounded-xl p-3 mb-2">
                <p class="text-xs font-semibold text-orange-700 mb-1">Tu selección del menú</p>
                <template x-for="item in cart" :key="item.id">
                    <div class="flex justify-between text-xs text-gray-600">
                        <span x-text="item.name + ' × ' + item.qty"></span>
                        <span class="font-medium text-orange-600" x-text="'$' + (item.price * item.qty).toLocaleString('es-CO')"></span>
                    </div>
                </template>
            </div>
            @endif

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Fecha</label>
                    <input type="date" name="reservation_date" required min="{{ now()->format('Y-m-d') }}"
                           class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary bg-gray-50 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Hora</label>
                    <input type="time" name="reservation_time" required
                           class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary bg-gray-50 transition-all">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-2">Personas</label>
                <div class="flex flex-wrap gap-2">
                    @foreach(range(1,8) as $n)
                    <label class="cursor-pointer">
                        <input type="radio" name="party_size" value="{{ $n }}" class="sr-only peer" {{ $n===2 ? 'checked' : '' }}>
                        <span class="w-9 h-9 rounded-xl border-2 border-gray-200 flex items-center justify-center text-xs font-bold text-gray-600 peer-checked:border-primary peer-checked:bg-primary peer-checked:text-white transition-all block">{{ $n }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Notas</label>
                <textarea name="notes" rows="2" placeholder="Alergias, ocasión especial…"
                          class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary bg-gray-50 resize-none transition-all"></textarea>
            </div>
            <button type="submit"
                    class="w-full bg-primary hover:bg-orange-600 text-white font-bold py-3.5 rounded-xl transition-all shadow-md shadow-orange-200 active:scale-95 text-sm">
                Solicitar Reserva y obtener QR
            </button>
        </form>
    </div>
</div>

{{-- ══ REVIEW MODAL ─────────────────────────────────────────────── --}}
<div x-show="reviewModal" x-cloak class="fixed inset-0 z-50 flex items-end sm:items-center justify-center">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="reviewModal = false"></div>
    <div class="relative bg-white w-full max-w-md rounded-t-3xl sm:rounded-3xl shadow-2xl z-10" x-data="{ rating: {{ $userReview?->rating ?? 0 }} }">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-heading text-base font-bold text-gray-900">{{ $userReview ? 'Editar' : 'Escribir' }} reseña</h3>
            <button @click="reviewModal = false" class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                <span class="material-symbols-outlined text-gray-500 text-[18px]">close</span>
            </button>
        </div>
        <form method="POST" action="{{ route('restaurante.review', $restaurant) }}" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-2">Puntuación</label>
                <div class="flex gap-2">
                    @foreach(range(1,5) as $star)
                    <label class="cursor-pointer">
                        <input type="radio" name="rating" value="{{ $star }}" class="sr-only peer" {{ ($userReview?->rating ?? 0) === $star ? 'checked' : '' }}
                               x-model="rating">
                        <span @click="rating = {{ $star }}"
                              class="material-symbols-outlined text-[32px] transition-all"
                              :style="rating >= {{ $star }} ? 'color: #f59e0b; font-variation-settings: FILL 1' : 'color: #d1d5db; font-variation-settings: FILL 0'">star</span>
                    </label>
                    @endforeach
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Título <span class="font-normal text-gray-400">(opcional)</span></label>
                <input type="text" name="title" value="{{ $userReview?->title }}" placeholder="Resumen de tu experiencia"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary bg-gray-50 transition-all">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Comentario <span class="font-normal text-gray-400">(opcional)</span></label>
                <textarea name="body" rows="3" placeholder="Cuéntanos tu experiencia…"
                          class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary bg-gray-50 resize-none transition-all">{{ $userReview?->body }}</textarea>
            </div>
            <button type="submit"
                    class="w-full bg-primary hover:bg-orange-600 text-white font-bold py-3 rounded-xl transition-all shadow-md shadow-orange-200 active:scale-95 text-sm">
                Publicar reseña
            </button>
        </form>
    </div>
</div>

<style>.no-scrollbar::-webkit-scrollbar{display:none}.no-scrollbar{-ms-overflow-style:none;scrollbar-width:none}</style>
</body>
</html>
