{{-- Preconnect: resuelve DNS de Google Fonts antes de que el CSS los solicite --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

{{--
    CSS y JS compilados por Vite.
    - Tailwind generado estáticamente (sin JIT en el navegador)
    - Fuentes Sora + Inter cargadas vía @import en app.css con font-display:swap
    - Material Symbols cargado vía @font-face con font-display:swap
    - Alpine.js incluido en el bundle
    Esto reemplaza los 3 scripts/links externos bloqueantes que causaban
    20+ segundos de carga (cdn.tailwindcss.com + 2x fonts.googleapis.com)
--}}
@vite(['resources/css/app.css', 'resources/js/app.js'])
