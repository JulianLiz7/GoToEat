<x-admin-layout :restaurant="$restaurant">
<x-slot name="title">Asistente IA</x-slot>

<div class="mb-8">
    <h2 class="text-3xl font-bold font-heading text-on-background">Asistente IA</h2>
    <p class="text-gray-500 mt-1">Análisis inteligente de tu operación con alertas proactivas.</p>
</div>

{{-- Alertas proactivas --}}
@if($alerts->isNotEmpty())
<div class="mb-6 space-y-3">
    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wide">Alertas activas</h3>
    @foreach($alerts as $alert)
    <div class="flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-2xl p-4">
        <span class="material-symbols-outlined text-amber-600 mt-0.5">{{ $alert['level'] === 'warning' ? 'warning' : 'info' }}</span>
        <div>
            <p class="text-sm font-semibold text-amber-800">{{ $alert['message'] }}</p>
            <p class="text-xs text-amber-600 mt-0.5 capitalize">{{ str_replace('_', ' ', $alert['type']) }}</p>
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- Chat con IA --}}
<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
        <div class="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center">
            <span class="material-symbols-outlined text-indigo-600 text-[20px]">smart_toy</span>
        </div>
        <div>
            <p class="font-semibold text-on-surface text-sm">GoToEat AI</p>
            <p class="text-[11px] text-emerald-600 font-semibold flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse inline-block"></span>
                Análisis en tiempo real
            </p>
        </div>
    </div>

    {{-- Conversaciones anteriores --}}
    <div class="h-80 overflow-y-auto p-6 space-y-4 bg-gray-50" id="chatMessages">
        @if($conversations->isEmpty())
        <div class="flex flex-col items-center justify-center h-full text-center">
            <span class="material-symbols-outlined text-5xl text-gray-200 mb-3">chat_bubble</span>
            <p class="text-gray-400 text-sm">Hazle una pregunta al asistente</p>
            <p class="text-gray-300 text-xs mt-1">Puedes preguntar sobre ventas, inventario o propinas</p>
        </div>
        @else
            @foreach($conversations as $conv)
            {{-- Pregunta --}}
            <div class="flex justify-end">
                <div class="max-w-[70%] bg-orange-500 text-white rounded-2xl rounded-tr-sm px-4 py-3 text-sm">
                    {{ is_string($conv->prompt) && strlen($conv->prompt) < 200 ? $conv->prompt : 'Análisis automático de orden' }}
                </div>
            </div>
            {{-- Respuesta --}}
            @if($conv->response)
            <div class="flex justify-start">
                <div class="max-w-[70%] bg-white border border-gray-100 rounded-2xl rounded-tl-sm px-4 py-3 text-sm text-gray-700 shadow-sm">
                    {{ $conv->response }}
                </div>
            </div>
            @else
            <div class="flex justify-start">
                <div class="bg-white border border-gray-100 rounded-2xl rounded-tl-sm px-4 py-3 shadow-sm">
                    <div class="flex gap-1">
                        <span class="w-2 h-2 bg-gray-300 rounded-full animate-bounce" style="animation-delay:0ms"></span>
                        <span class="w-2 h-2 bg-gray-300 rounded-full animate-bounce" style="animation-delay:150ms"></span>
                        <span class="w-2 h-2 bg-gray-300 rounded-full animate-bounce" style="animation-delay:300ms"></span>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
        @endif
    </div>

    {{-- Input --}}
    <form action="{{ route('admin.ai.ask') }}" method="POST"
          class="border-t border-gray-100 p-4 flex gap-3">
        @csrf
        <input type="text" name="prompt" required
               placeholder="Pregunta algo: ¿cuáles son los productos más vendidos esta semana?"
               class="flex-1 bg-gray-50 border-none rounded-full px-5 py-3 text-sm focus:ring-2 focus:ring-orange-500/20 placeholder-gray-400"/>
        <button type="submit"
                class="w-11 h-11 bg-orange-500 text-white rounded-full flex items-center justify-center hover:bg-orange-600 active:scale-95 transition-all shadow-sm shadow-orange-200 shrink-0">
            <span class="material-symbols-outlined text-[20px]">send</span>
        </button>
    </form>
</div>

<script>
// Auto-scroll al último mensaje
document.getElementById('chatMessages').scrollTop = document.getElementById('chatMessages').scrollHeight;
</script>

</x-admin-layout>
