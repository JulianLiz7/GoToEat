import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

/**
 * Iniciamos Alpine solo si Livewire no lo hace primero.
 * Livewire 4 llama Alpine.start() internamente. Si ya está iniciado,
 * alpinejs retorna sin error. El chequeo previene doble inicialización.
 */
document.addEventListener('DOMContentLoaded', () => {
    if (!document.querySelector('[wire\\:id]')) {
        // Página sin componentes Livewire → Alpine arranca solo
        Alpine.start();
    }
    // En páginas con Livewire, @livewireScripts inicia Alpine automáticamente
});
