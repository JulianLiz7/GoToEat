<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8"/>
<title>Cierre de Caja — {{ $restaurant->name }}</title>
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; font-family: Arial, sans-serif; }
    body { padding: 32px; font-size: 13px; color: #111; background: #fff; }
    h1  { font-size: 22px; color: #f97316; margin-bottom: 4px; }
    h2  { font-size: 14px; font-weight: 700; margin: 24px 0 8px;
          border-bottom: 2px solid #f97316; padding-bottom: 4px; }
    .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; }
    .meta   { font-size: 11px; color: #9ca3af; line-height: 1.6; text-align: right; }
    .kpi { display: flex; gap: 16px; margin: 16px 0; }
    .kpi-card { flex: 1; background: #f9fafb; border-radius: 8px; padding: 14px; }
    .kpi-card label { font-size: 10px; text-transform: uppercase; color: #9ca3af;
                      display: block; margin-bottom: 4px; letter-spacing: .04em; }
    .kpi-card span  { font-size: 22px; font-weight: 900; }
    .orange { color: #f97316; }
    .green  { color: #006c49; }
    .red    { color: #ba1a1a; }
    table { width: 100%; border-collapse: collapse; font-size: 12px; margin-bottom: 8px; }
    th  { background: #f9fafb; padding: 8px 12px; text-align: left;
          font-size: 11px; text-transform: uppercase; color: #6b7280; }
    td  { padding: 7px 12px; border-bottom: 1px solid #f3f4f6; }
    .total-row td { font-weight: 900; border-top: 2px solid #f97316; background: #fff8f5; }
    .notes-box { background: #f9fafb; border-left: 3px solid #f97316;
                 padding: 10px 14px; border-radius: 0 6px 6px 0; margin: 16px 0;
                 font-size: 12px; color: #584237; }
    .footer { margin-top: 40px; font-size: 10px; color: #9ca3af;
              border-top: 1px solid #e5e7eb; padding-top: 12px;
              display: flex; justify-content: space-between; }
    @media print {
        body { padding: 20px; }
        @page { margin: 1cm; size: A4; }
    }
</style>
</head>
<body>

<div class="header">
    <div>
        <h1>GoToEat</h1>
        <p style="font-size:13px;font-weight:700;margin-top:2px">{{ $restaurant->name }}</p>
        <p style="font-size:11px;color:#9ca3af;margin-top:2px">Cierre de Caja — Reporte del Día</p>
    </div>
    <div class="meta">
        <p><strong>Fecha:</strong> {{ now()->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}</p>
        <p><strong>Responsable:</strong> {{ auth()->user()->name }}</p>
        <p><strong>Órdenes procesadas:</strong> {{ $todayOrdersCount }}</p>
    </div>
</div>

{{-- KPIs --}}
<div class="kpi">
    <div class="kpi-card">
        <label>Efectivo Esperado (POS)</label>
        <span class="orange">${{ number_format($expectedCash, 0, ',', '.') }}</span>
    </div>
    <div class="kpi-card">
        <label>Efectivo Contado</label>
        <span class="orange">${{ number_format($cashCounted, 0, ',', '.') }}</span>
    </div>
    <div class="kpi-card">
        <label>{{ $difference >= 0 ? 'Sobrante' : 'Faltante' }}</label>
        <span class="{{ $difference >= 0 ? 'green' : 'red' }}">
            {{ $difference >= 0 ? '+' : '-' }}${{ number_format(abs($difference), 0, ',', '.') }}
        </span>
    </div>
</div>

{{-- Billetes --}}
@php
    $billetes = array_values(array_filter($denominations, fn($d) => ($d['type'] ?? '') === 'billete'));
    $monedas  = array_values(array_filter($denominations, fn($d) => ($d['type'] ?? '') === 'moneda'));
@endphp

@if(count($billetes) > 0)
<h2>Billetes Contados</h2>
<table>
    <thead><tr><th>Denominación</th><th style="text-align:right">Cantidad</th><th style="text-align:right">Subtotal</th></tr></thead>
    <tbody>
        @foreach($billetes as $b)
        <tr>
            <td>{{ $b['label'] }}</td>
            <td style="text-align:right">{{ (int)$b['qty'] }}</td>
            <td style="text-align:right;font-weight:700">${{ number_format($b['value'] * (int)$b['qty'], 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

@if(count($monedas) > 0)
<h2>Monedas Contadas</h2>
<table>
    <thead><tr><th>Denominación</th><th style="text-align:right">Cantidad</th><th style="text-align:right">Subtotal</th></tr></thead>
    <tbody>
        @foreach($monedas as $m)
        <tr>
            <td>{{ $m['label'] }}</td>
            <td style="text-align:right">{{ (int)$m['qty'] }}</td>
            <td style="text-align:right;font-weight:700">${{ number_format($m['value'] * (int)$m['qty'], 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

{{-- Resumen --}}
<h2>Resumen de Cierre</h2>
<table>
    <tbody>
        <tr>
            <td>Ventas del día (POS)</td>
            <td style="text-align:right;color:#006c49;font-weight:700">${{ number_format($expectedCash, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Egresos del día</td>
            <td style="text-align:right;color:#ba1a1a;font-weight:700">-${{ number_format($todayExpenses, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Efectivo contado físicamente</td>
            <td style="text-align:right;font-weight:700">${{ number_format($cashCounted, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>{{ $difference >= 0 ? 'Sobrante' : 'Faltante' }} de caja</td>
            <td style="text-align:right;font-weight:700;color:{{ $difference >= 0 ? '#006c49' : '#ba1a1a' }}">
                {{ $difference >= 0 ? '+' : '-' }}${{ number_format(abs($difference), 0, ',', '.') }}
            </td>
        </tr>
        <tr class="total-row">
            <td>Monto a depositar</td>
            <td style="text-align:right">${{ number_format($depositAmount, 0, ',', '.') }}</td>
        </tr>
    </tbody>
</table>

@if($notes)
<div class="notes-box">
    <strong>Observaciones:</strong> {{ $notes }}
</div>
@endif

<div class="footer">
    <span>GoToEat Restaurant Management — Documento confidencial generado automáticamente.</span>
    <span>{{ now()->format('d/m/Y') }}</span>
</div>

<script>window.addEventListener('load', function() { window.print(); });</script>
</body>
</html>
