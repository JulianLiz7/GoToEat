<x-admin-layout :restaurant="$restaurant">
<x-slot name="title">Configuración</x-slot>

<div class="mb-8">
    <h2 class="text-3xl font-black text-on-surface">Configuración del Local</h2>
    <p class="text-gray-500 mt-1 text-sm">Personaliza la información y apariencia que verán tus comensales.</p>
</div>

<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data"
      x-data="{
          tab: 'info',
          primary: '{{ $restaurant->primary_color ?? '#f97316' }}',
          secondary: '{{ $restaurant->secondary_color ?? '#006c49' }}',
          logoPreview: '{{ $restaurant->logo_path ? Storage::url($restaurant->logo_path) : '' }}',
          coverPreview: '{{ $restaurant->cover_path ? Storage::url($restaurant->cover_path) : '' }}',
          presetColors: ['#f97316','#e11d48','#7c3aed','#2563eb','#0891b2','#059669','#ca8a04','#dc2626','#1d4ed8','#6d28d9','#0f766e','#000000'],
          handleLogoChange(e) {
              const file = e.target.files[0];
              if (file) this.logoPreview = URL.createObjectURL(file);
          },
          handleCoverChange(e) {
              const file = e.target.files[0];
              if (file) this.coverPreview = URL.createObjectURL(file);
          }
      }">
    @csrf

    {{-- Tabs ──────────────────────────────────────────────────── --}}
    <div class="flex gap-1 mb-8 bg-gray-100 p-1 rounded-2xl w-fit">
        @foreach(['info' => ['Información General','store'], 'apariencia' => ['Apariencia','palette'], 'contacto' => ['Horarios y Contacto','schedule']] as $key => [$label, $icon])
        <button type="button" @click="tab = '{{ $key }}'"
                class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200"
                :class="tab === '{{ $key }}'
                    ? 'bg-white text-primary shadow-sm border border-gray-200'
                    : 'text-gray-500 hover:text-on-surface'">
            <span class="material-symbols-outlined text-[18px]">{{ $icon }}</span>
            {{ $label }}
        </button>
        @endforeach
    </div>

    {{-- ══ TAB: Información General ══════════════════════════════ --}}
    <div x-show="tab === 'info'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
                    <h3 class="font-bold text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary-container text-[22px]">storefront</span>
                        Datos del Restaurante
                    </h3>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5">Nombre del Restaurante *</label>
                        <input name="name" type="text" required value="{{ old('name', $restaurant->name) }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all"/>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5">Descripción
                            <span class="font-normal normal-case text-gray-300 ml-1">— Visible para los comensales</span>
                        </label>
                        <textarea name="description" rows="3"
                                  placeholder="Cuéntales a tus clientes qué hace especial a tu restaurante..."
                                  class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all resize-none">{{ old('description', $restaurant->description) }}</textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5">Categoría</label>
                            <select name="category"
                                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all">
                                @foreach(['Restaurante','Cafetería','Pizzería','Sushi','Panadería','Bar','Fast Food','Comida Rápida','Mariscos','Vegetariano','Otro'] as $cat)
                                <option value="{{ $cat }}" {{ old('category', $restaurant->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5">Tipo de Cocina</label>
                            <input name="cuisine_type" type="text"
                                   placeholder="Ej: Italiana, Colombiana, Fusión..."
                                   value="{{ old('cuisine_type', $restaurant->cuisine_type) }}"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all"/>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Preview card --}}
            <div class="space-y-4">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                    <p class="text-xs font-bold uppercase tracking-wide text-gray-400 mb-3">Vista previa — tarjeta del comensal</p>
                    <div class="rounded-xl border border-gray-100 overflow-hidden shadow-sm">
                        <div class="h-24 relative"
                             :style="coverPreview ? `background-image:url('${coverPreview}');background-size:cover;background-position:center` : ''"
                             :class="!coverPreview ? 'bg-gradient-to-br from-gray-100 to-gray-200' : ''">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                            <div class="absolute bottom-3 left-3">
                                <div class="w-10 h-10 rounded-xl overflow-hidden border-2 border-white shadow-sm"
                                     :style="'background-color:' + primary">
                                    <template x-if="logoPreview">
                                        <img :src="logoPreview" class="w-full h-full object-cover"/>
                                    </template>
                                    <template x-if="!logoPreview">
                                        <span class="material-symbols-outlined text-white text-[22px] flex items-center justify-center h-full" style="font-variation-settings:'FILL' 1">restaurant</span>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <div class="p-3">
                            <p class="font-bold text-sm text-on-surface">{{ $restaurant->name }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $restaurant->cuisine_type ?? 'Tipo de cocina' }}</p>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="text-xs font-bold px-2 py-0.5 rounded-full text-white"
                                      :style="'background-color:' + primary">
                                    {{ $restaurant->category ?? 'Categoría' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ TAB: Apariencia ════════════════════════════════════════ --}}
    <div x-show="tab === 'apariencia'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Logo --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-on-surface mb-1 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary-container text-[22px]">image</span>
                    Logo del Restaurante
                </h3>
                <p class="text-xs text-gray-400 mb-4">Aparece en tu tarjeta y en la cabecera de tu página. PNG transparente recomendado.</p>

                <div class="flex items-start gap-5">
                    {{-- Preview --}}
                    <div class="w-24 h-24 rounded-2xl border-2 border-dashed border-gray-200 flex items-center justify-center shrink-0 overflow-hidden bg-gray-50"
                         :class="logoPreview ? 'border-solid border-primary-container/30' : ''">
                        <template x-if="logoPreview">
                            <img :src="logoPreview" class="w-full h-full object-contain p-2"/>
                        </template>
                        <template x-if="!logoPreview">
                            <span class="material-symbols-outlined text-3xl text-gray-300">add_photo_alternate</span>
                        </template>
                    </div>
                    {{-- Actions --}}
                    <div class="flex-1 space-y-3">
                        <label class="flex items-center gap-2 px-4 py-2.5 border-2 border-dashed border-gray-200 rounded-xl cursor-pointer hover:border-primary-container hover:bg-orange-50/40 transition-all text-sm font-semibold text-gray-600">
                            <span class="material-symbols-outlined text-[18px] text-primary-container">upload</span>
                            Subir logo
                            <input type="file" name="logo" accept=".jpg,.jpeg,.png,.webp,.svg" class="hidden" @change="handleLogoChange"/>
                        </label>
                        <p class="text-xs text-gray-400">JPG, PNG, SVG o WEBP — máx. 5 MB</p>
                        @if($restaurant->logo_path)
                        <form method="POST" action="{{ route('admin.settings.logo.delete') }}" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-error hover:underline flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">delete</span>
                                Eliminar logo actual
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Foto de portada --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-on-surface mb-1 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary-container text-[22px]">photo_camera</span>
                    Foto de Portada
                </h3>
                <p class="text-xs text-gray-400 mb-4">Imagen de cabecera en tu tarjeta del menú. Horizontal, mínimo 800×400 px.</p>

                <div class="relative rounded-xl overflow-hidden border border-gray-200 cursor-pointer group"
                     style="height:120px" onclick="document.getElementById('coverInput').click()">
                    <template x-if="coverPreview">
                        <img :src="coverPreview" class="w-full h-full object-cover"/>
                    </template>
                    <template x-if="!coverPreview">
                        <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex flex-col items-center justify-center gap-1">
                            <span class="material-symbols-outlined text-3xl text-gray-300">panorama</span>
                            <p class="text-xs text-gray-400">Haz clic para subir portada</p>
                        </div>
                    </template>
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-3xl">upload</span>
                    </div>
                </div>
                <input id="coverInput" type="file" name="cover" accept=".jpg,.jpeg,.png,.webp" class="hidden" @change="handleCoverChange"/>
                <div class="flex items-center justify-between mt-2">
                    <p class="text-xs text-gray-400">JPG o PNG — máx. 10 MB</p>
                    @if($restaurant->cover_path)
                    <form method="POST" action="{{ route('admin.settings.cover.delete') }}">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs text-error hover:underline flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">delete</span>
                            Eliminar portada
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            {{-- Colores --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:col-span-2">
                <h3 class="font-bold text-on-surface mb-1 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary-container text-[22px]">palette</span>
                    Colores de Marca
                </h3>
                <p class="text-xs text-gray-400 mb-5">Estos colores se aplican en tu tarjeta del menú y en los botones de reserva que verán tus comensales.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Color principal --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-3">Color Principal</label>
                        <div class="flex items-center gap-4 mb-4">
                            <div class="relative">
                                <input type="color" name="primary_color" x-model="primary"
                                       class="w-16 h-16 rounded-2xl border-2 border-gray-200 cursor-pointer p-1"/>
                            </div>
                            <div>
                                <p class="font-bold text-on-surface" x-text="primary.toUpperCase()"></p>
                                <p class="text-xs text-gray-400">Hex del color seleccionado</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mb-2 uppercase font-bold tracking-wide">Paleta sugerida</p>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="color in presetColors" :key="color">
                                <button type="button" @click="primary = color"
                                        class="w-8 h-8 rounded-lg transition-all hover:scale-110 active:scale-95 border-2"
                                        :style="'background-color:' + color"
                                        :class="primary === color ? 'border-on-surface scale-110 shadow-md' : 'border-transparent'">
                                </button>
                            </template>
                        </div>
                    </div>

                    {{-- Color secundario --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-3">Color Secundario</label>
                        <div class="flex items-center gap-4 mb-4">
                            <input type="color" name="secondary_color" x-model="secondary"
                                   class="w-16 h-16 rounded-2xl border-2 border-gray-200 cursor-pointer p-1"/>
                            <div>
                                <p class="font-bold text-on-surface" x-text="secondary.toUpperCase()"></p>
                                <p class="text-xs text-gray-400">Hex del color seleccionado</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mb-2 uppercase font-bold tracking-wide">Paleta sugerida</p>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="color in presetColors" :key="color">
                                <button type="button" @click="secondary = color"
                                        class="w-8 h-8 rounded-lg transition-all hover:scale-110 active:scale-95 border-2"
                                        :style="'background-color:' + color"
                                        :class="secondary === color ? 'border-on-surface scale-110 shadow-md' : 'border-transparent'">
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Preview combinado --}}
                <div class="mt-6 p-4 bg-gray-50 rounded-xl">
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wide mb-3">Cómo se verá tu restaurante para los comensales</p>
                    <div class="flex items-center gap-4 flex-wrap">
                        <div class="flex items-center gap-3 bg-white rounded-xl px-4 py-3 shadow-sm border border-gray-100">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center" :style="'background-color:' + primary">
                                <span class="material-symbols-outlined text-white text-[20px]" style="font-variation-settings:'FILL' 1">restaurant</span>
                            </div>
                            <div>
                                <p class="font-bold text-sm">{{ $restaurant->name }}</p>
                                <p class="text-xs" :style="'color:' + secondary">{{ $restaurant->cuisine_type ?? 'Tipo de cocina' }}</p>
                            </div>
                        </div>
                        <button type="button" class="px-5 py-2.5 rounded-xl text-white font-bold text-sm shadow-sm transition-all"
                                :style="'background-color:' + primary">
                            Ver Menú
                        </button>
                        <span class="px-3 py-1 rounded-full text-white text-xs font-bold"
                              :style="'background-color:' + secondary">
                            {{ $restaurant->category ?? 'Restaurante' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ TAB: Horarios y Contacto ═══════════════════════════════ --}}
    <div x-show="tab === 'contacto'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
                <h3 class="font-bold text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary-container text-[22px]">contact_phone</span>
                    Información de Contacto
                </h3>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5">Dirección</label>
                    <input name="address" type="text" placeholder="Calle 10 #25-30, Bucaramanga"
                           value="{{ old('address', $restaurant->address) }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all"/>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5">Teléfono</label>
                        <input name="phone" type="tel" placeholder="+57 300 000 0000"
                               value="{{ old('phone', $restaurant->phone) }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all"/>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5">WhatsApp</label>
                        <input name="whatsapp" type="tel" placeholder="+57 300 000 0000"
                               value="{{ old('whatsapp', $restaurant->whatsapp ?? '') }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all"/>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5">Correo Electrónico</label>
                    <input name="email" type="email" placeholder="contacto@mirestaurante.com"
                           value="{{ old('email', $restaurant->email) }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all"/>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5">Sitio Web</label>
                    <input name="website" type="url" placeholder="https://www.mirestaurante.com"
                           value="{{ old('website', $restaurant->website ?? '') }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all"/>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
                <h3 class="font-bold text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary-container text-[22px]">schedule</span>
                    Horario de Atención
                </h3>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5">Horario</label>
                    <input name="opening_hours" type="text"
                           placeholder="Lun–Vie: 12:00–22:00 | Sáb–Dom: 12:00–23:00"
                           value="{{ old('opening_hours', $restaurant->opening_hours ?? '') }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all"/>
                    <p class="text-xs text-gray-400 mt-1">Describe el horario como quieras que lo vean tus clientes.</p>
                </div>

                {{-- Info extra --}}
                <div class="bg-surface-container/50 rounded-xl p-4 border border-outline-variant/20">
                    <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wide mb-2 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">info</span>
                        Datos actuales del restaurante
                    </p>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Estado</span>
                            <span class="font-semibold {{ $restaurant->status === 'active' ? 'text-secondary' : 'text-error' }}">
                                {{ $restaurant->status === 'active' ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Slug (URL)</span>
                            <span class="font-semibold text-on-surface font-mono text-xs">{{ $restaurant->slug }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Creado</span>
                            <span class="font-semibold text-on-surface">{{ $restaurant->created_at->format('d M, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Botón guardar (siempre visible) ──────────────────────────── --}}
    <div class="mt-8 flex items-center justify-between bg-white rounded-2xl px-6 py-4 shadow-sm border border-gray-100">
        @if(session('success'))
        <div class="flex items-center gap-2 text-secondary">
            <span class="material-symbols-outlined text-[20px]" style="font-variation-settings:'FILL' 1">check_circle</span>
            <span class="font-semibold text-sm">{{ session('success') }}</span>
        </div>
        @else
        <p class="text-sm text-gray-400">Los cambios se reflejan inmediatamente en el perfil de tu restaurante.</p>
        @endif

        <button type="submit"
                class="flex items-center gap-2 px-6 py-2.5 bg-primary-container text-white rounded-xl font-bold text-sm
                       hover:bg-primary active:scale-[0.97] transition-all shadow-md shadow-orange-200">
            <span class="material-symbols-outlined text-[18px]">save</span>
            Guardar Cambios
        </button>
    </div>

</form>

</x-admin-layout>
