# ── GoToEat Dev Helper ──────────────────────────────────────────────
# Uso: .\dev-refresh.ps1          → recarga PHP tras cambios de código
#      .\dev-refresh.ps1 -full    → también limpia y recrea caches
param([switch]$full)

Write-Host "GoToEat Dev Refresh..." -ForegroundColor Cyan

if ($full) {
    Write-Host "Limpiando caches..." -ForegroundColor Yellow
    docker exec laravel_app php artisan config:clear 2>&1 | Out-Null
    docker exec laravel_app php artisan route:clear  2>&1 | Out-Null
    docker exec laravel_app php artisan view:clear   2>&1 | Out-Null
}

# Reiniciar PHP-FPM para limpiar OPcache (validate_timestamps=0)
Write-Host "Recargando PHP-FPM..." -ForegroundColor Yellow
docker restart laravel_app | Out-Null
Start-Sleep -Seconds 4

# Reconstruir caches de Laravel
Write-Host "Reconstruyendo caches de Laravel..." -ForegroundColor Yellow
docker exec laravel_app php artisan config:cache 2>&1 | Out-Null
docker exec laravel_app php artisan route:cache  2>&1 | Out-Null
docker exec laravel_app php artisan view:cache   2>&1 | Out-Null

Write-Host "Listo. La app deberia responder rapido ahora." -ForegroundColor Green
Write-Host ""
Write-Host "URLs disponibles:" -ForegroundColor Cyan
Write-Host "  App:     http://localhost:8000" -ForegroundColor White
Write-Host "  phpMyAdmin: http://localhost:8080" -ForegroundColor White
