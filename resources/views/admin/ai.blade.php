<x-admin-layout :restaurant="$restaurant">
<x-slot name="title">AI Assistant</x-slot>

{{-- Layout de dos columnas: chat (izq) + contexto (der) --}}
<div class="flex gap-6 h-[calc(100vh-8rem)]">

    {{-- ══ PANEL IZQUIERDO: Chat ═══════════════════════════════ --}}
    <section class="flex-1 flex flex-col bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Encabezado del asistente --}}
        <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between bg-white/60 backdrop-blur-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-orange-500 flex items-center justify-center shadow-lg shadow-orange-200">
                    <span class="material-symbols-outlined text-white text-2xl" style="font-variation-settings:'FILL' 1">smart_toy</span>
                </div>
                <div>
                    <h2 class="font-heading font-bold text-lg text-on-surface">GoToEat AI Assistant</h2>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                        <span class="text-xs text-gray-500">Live &amp; connected to your data</span>
                    </div>
                </div>
            </div>
            <button class="p-2 hover:bg-gray-100 rounded-xl transition-colors text-gray-400 hover:text-gray-600">
                <span class="material-symbols-outlined">more_vert</span>
            </button>
        </div>

        {{-- Área de mensajes con patrón punteado --}}
        <div class="flex-1 overflow-y-auto p-8 space-y-8"
             id="chatMessages"
             style="background-image: radial-gradient(#f1f5f9 1px, transparent 1px); background-size: 20px 20px;">

            @if($conversations->isEmpty())
                {{-- Saludo inicial del sistema --}}
                <div class="flex gap-4 max-w-2xl">
                    <div class="w-8 h-8 rounded-xl bg-orange-100 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-orange-600 text-sm" style="font-variation-settings:'FILL' 1">smart_toy</span>
                    </div>
                    <div class="space-y-2">
                        <div class="bg-orange-50 text-on-surface p-4 rounded-2xl rounded-tl-none shadow-sm border border-orange-100/50">
                            <p class="text-sm">¡Hola, <span class="font-bold text-orange-600">{{ auth()->user()->name }}</span>!
                            Soy tu asistente de IA. Puedo analizar tus ventas, inventario y propinas.
                            ¿En qué te ayudo hoy?</p>
                        </div>
                        <span class="text-[10px] text-gray-400 font-medium px-1 uppercase tracking-wider">AI ASSISTANT • Ahora</span>
                    </div>
                </div>

                @if($alerts->isNotEmpty())
                <div class="flex gap-4 max-w-2xl">
                    <div class="w-8 h-8 rounded-xl bg-orange-100 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-orange-600 text-sm" style="font-variation-settings:'FILL' 1">smart_toy</span>
                    </div>
                    <div class="space-y-3 flex-1">
                        <div class="bg-amber-50 border border-amber-200 text-on-surface p-4 rounded-2xl rounded-tl-none shadow-sm">
                            <p class="text-sm font-semibold text-amber-800 mb-3">
                                <span class="material-symbols-outlined text-[16px] align-middle mr-1">warning</span>
                                {{ $alerts->count() }} alerta(s) detectada(s) en inventario:
                            </p>
                            @foreach($alerts as $alert)
                            <div class="flex items-center justify-between p-3 bg-white rounded-xl border border-amber-100 mt-2">
                                <span class="text-sm font-medium text-on-surface">{{ $alert['message'] }}</span>
                                <span class="text-[10px] text-amber-600 font-bold uppercase bg-amber-100 px-2 py-0.5 rounded-full">{{ str_replace('_',' ', $alert['type']) }}</span>
                            </div>
                            @endforeach
                        </div>
                        <span class="text-[10px] text-gray-400 font-medium px-1 uppercase tracking-wider">AI ASSISTANT • Alerta automática</span>
                    </div>
                </div>
                @endif

            @else
                {{-- Conversaciones almacenadas --}}
                @foreach($conversations as $conv)
                {{-- Pregunta del usuario --}}
                @php $isAutomatic = strlen($conv->prompt) > 200 || str_starts_with($conv->prompt, '{'); @endphp
                @if(!$isAutomatic)
                <div class="flex gap-4 max-w-2xl ml-auto flex-row-reverse">
                    <div class="w-8 h-8 rounded-xl bg-gray-100 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-gray-600 text-sm">person</span>
                    </div>
                    <div class="space-y-2 text-right">
                        <div class="bg-on-surface text-white p-4 rounded-2xl rounded-tr-none shadow-sm">
                            <p class="text-sm">{{ $conv->prompt }}</p>
                        </div>
                        <span class="text-[10px] text-gray-400 font-medium px-1 uppercase tracking-wider">
                            TÚ • {{ $conv->created_at->format('H:i') }}
                        </span>
                    </div>
                </div>
                @endif

                {{-- Respuesta del asistente --}}
                <div class="flex gap-4 max-w-2xl">
                    <div class="w-8 h-8 rounded-xl bg-orange-100 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-orange-600 text-sm" style="font-variation-settings:'FILL' 1">smart_toy</span>
                    </div>
                    <div class="space-y-2">
                        @if($conv->response)
                        @php
                        $responseData = json_decode($conv->response, true);
                        $responseText = isset($responseData['message']) ? $responseData['message'] : $conv->response;
                        @endphp
                        <div class="bg-orange-50 text-on-surface p-4 rounded-2xl rounded-tl-none shadow-sm border border-orange-100/50">
                            <p class="text-sm">{{ $responseText }}</p>
                        </div>
                        @else
                        {{-- Indicador de escritura --}}
                        <div class="bg-white border border-gray-100 rounded-2xl rounded-tl-none px-5 py-4 shadow-sm">
                            <div class="flex gap-1.5 items-center">
                                <span class="w-2 h-2 bg-gray-300 rounded-full animate-bounce" style="animation-delay:0ms"></span>
                                <span class="w-2 h-2 bg-gray-300 rounded-full animate-bounce" style="animation-delay:150ms"></span>
                                <span class="w-2 h-2 bg-gray-300 rounded-full animate-bounce" style="animation-delay:300ms"></span>
                            </div>
                        </div>
                        @endif
                        <span class="text-[10px] text-gray-400 font-medium px-1 uppercase tracking-wider">
                            AI ASSISTANT • {{ $conv->created_at->format('H:i') }}
                        </span>
                    </div>
                </div>
                @endforeach
            @endif
        </div>

        {{-- Input area --}}
        <div class="p-6 bg-white border-t border-gray-50">
            {{-- Sugerencias rápidas --}}
            <div class="flex items-center gap-2 mb-4 overflow-x-auto pb-1">
                @foreach([
                    '¿Cuál fue el plato más vendido esta semana?',
                    'Revisar rendimiento del personal',
                    'Mostrar alertas de inventario',
                ] as $i => $suggestion)
                <button type="button"
                        onclick="document.getElementById('promptInput').value = '{{ $suggestion }}'"
                        class="whitespace-nowrap px-4 py-1.5 rounded-full border text-xs font-semibold transition-colors
                               {{ $i === 0
                                   ? 'border-orange-200 bg-orange-50 text-orange-600 hover:bg-orange-100'
                                   : 'border-gray-100 bg-gray-50 text-gray-600 hover:bg-gray-100' }}">
                    {{ $suggestion }}
                </button>
                @endforeach
            </div>

            <form action="{{ route('admin.ai.ask') }}" method="POST">
                @csrf
                <div class="relative">
                    <input id="promptInput"
                           name="prompt"
                           type="text"
                           required
                           placeholder="Pregunta sobre tu restaurante..."
                           class="w-full pl-6 pr-16 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all text-sm outline-none"/>
                    <button type="submit"
                            class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-orange-500 text-white rounded-xl flex items-center justify-center hover:bg-orange-600 transition-all active:scale-95 shadow-sm shadow-orange-200">
                        <span class="material-symbols-outlined text-[20px]">send</span>
                    </button>
                </div>
            </form>
        </div>
    </section>

    {{-- ══ PANEL DERECHO: Contexto ════════════════════════════ --}}
    <aside class="w-80 flex flex-col gap-4 overflow-y-auto">

        {{-- Estado actual --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 shrink-0">
            <h3 class="text-sm font-bold text-on-surface mb-4">Estado Actual</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-emerald-50 rounded-xl border border-emerald-100">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-emerald-600 text-[20px]" style="font-variation-settings:'FILL' 1">check_circle</span>
                        <span class="text-sm font-medium text-emerald-800">Sistema Online</span>
                    </div>
                    <span class="text-[10px] text-emerald-600 font-bold bg-emerald-100 px-2 py-0.5 rounded-full">LIVE</span>
                </div>

                @if($alerts->isNotEmpty())
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-100">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-amber-500 text-[20px]">warning</span>
                        <span class="text-sm font-medium text-gray-700">{{ $alerts->count() }} ítems bajo stock</span>
                    </div>
                    @if(Route::has('admin.inventory'))
                    <a href="{{ route('admin.inventory') }}" class="text-[10px] text-orange-600 font-bold hover:underline">VER</a>
                    @endif
                </div>
                @else
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-100">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-gray-400 text-[20px]" style="font-variation-settings:'FILL' 1">inventory_2</span>
                        <span class="text-sm font-medium text-gray-600">Inventario en orden</span>
                    </div>
                    <span class="text-[10px] text-emerald-600 font-bold">OK</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Historial de conversaciones --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex-1">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-sm font-bold text-on-surface">Historial Reciente</h3>
                <button class="text-orange-500 hover:bg-orange-50 p-1 rounded-lg transition-colors">
                    <span class="material-symbols-outlined text-lg">history</span>
                </button>
            </div>

            @if($conversations->where(fn($c) => strlen($c->prompt) < 200)->isEmpty())
                <p class="text-xs text-gray-400 text-center py-6">Sin conversaciones aún</p>
            @else
                <div class="space-y-4">
                    @foreach($conversations->where(fn($c) => strlen($c->prompt) < 200)->take(5) as $conv)
                    <div class="group">
                        <p class="text-xs text-gray-400 mb-0.5">{{ $conv->created_at->format('d M, H:i') }}</p>
                        <p class="text-sm font-medium text-on-surface group-hover:text-orange-500 transition-colors line-clamp-1">
                            {{ $conv->prompt }}
                        </p>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Tarjeta promo --}}
        <div class="relative rounded-2xl overflow-hidden shadow-md bg-on-surface shrink-0">
            <div class="p-6">
                <span class="text-[10px] font-bold text-orange-400 tracking-widest uppercase mb-2 block">Nueva Función</span>
                <h4 class="text-white font-bold text-base mb-2">Inventario Predictivo</h4>
                <p class="text-gray-400 text-xs leading-relaxed mb-4">
                    La IA pronostica tus necesidades de ingredientes según patrones históricos.
                </p>
                <button class="w-full bg-white text-on-surface py-2.5 rounded-xl font-bold text-xs hover:bg-orange-50 transition-colors">
                    Saber más
                </button>
            </div>
        </div>
    </aside>
</div>

@push('scripts')
<script>
    // Auto-scroll al último mensaje
    const chat = document.getElementById('chatMessages');
    if (chat) chat.scrollTop = chat.scrollHeight;
</script>
@endpush

</x-admin-layout>
