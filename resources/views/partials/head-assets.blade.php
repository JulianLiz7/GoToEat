{{-- Preconnect: establece conexiones TCP/TLS con Google antes de que los links las necesiten --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

{{-- Fuentes: cargadas como <link> directos (paralelo al CSS de Vite, no secuencial como @import).
     Esto evita el FOUT (Flash Of Unstyled Text) que hacía que el layout se viera
     desplazado mientras la fuente del sistema se usaba como fallback. --}}
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&family=Inter:wght@400;500;600&display=swap"
      rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap"
      rel="stylesheet">

{{-- CSS y JS compilados por Vite (Tailwind + Alpine) --}}
@vite(['resources/css/app.css', 'resources/js/app.js'])
