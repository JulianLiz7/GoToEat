{{-- Preconnect: resuelve DNS/TLS de Google Fonts antes de que el CSS los solicite --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

{{-- CSS y JS compilados por Vite (Tailwind + Alpine + fuentes via @import en app.css) --}}
@vite(['resources/css/app.css', 'resources/js/app.js'])
