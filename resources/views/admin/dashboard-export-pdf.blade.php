<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8"/>
<title>Panel Operativo — {{ $restaurant->name }}</title>
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; font-family: Arial, sans-serif; }
    body { padding: 32px; font-size: 13px; color: #111; background: #fff; }
    h1   { font-size: 22px; color: #f97316; margin-bottom: 4px; }
    h2   { font-size: 14px; font-weight: 700; margin: 24px 0 10px;
           border-bottom: 2px solid #f97316; padding-bottom: 4px; }
    .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 28px; }
    .meta   { font-size: 11px; color: #9ca3af; line-height: 1.7; text-align: right; }
    .kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 8px; }
    .kpi { background: #f9fafb; border-radius: 8px; padding: 14px; border-left: 3px solid #f97316; }
    .kpi.green  { border-left-color: #006c49; }
    .kpi.red    { border-left-color: #ba1a1a; }
    .kpi.purple { border-left-color: #7c3aed; }
    .kpi.blue   { border-left-color: #2563eb; }
    .kpi label  { font-size: 10px; text-transform: uppercase; color: #9ca3af;
                  display: block; margin-bottom: 5px; letter-spacing: .04em; }
    .kpi span   { font-size: 20px; font-weight: 900; }
    .kpi .trend { font-size: 11px; color: #9ca3af; margin-top: 3px; }
    .kpi.green span { color: #006c49; }
    .kpi.red    span { color: #ba1a1a; }
    .kpi.purple span { color: #7c3aed; }
    .kpi.blue   span { color: #2563eb; }
    table { width: 100%; border-collapse: collapse; font-size: 12px; }
    th  { background: #f9fafb; padding: 8px 12px; text-align: left;
          font-size: 11px; text-transform: uppercase; color: #6b7280; }
    td  { padding: 8px 12px; border-bottom: 1px solid #f3f4f6; }
    .badge { display: inline-block; padding: 2px 8px; border-radius: 20px;
             font-size: 10px; font-weight: 700; }
    .badge.green { background: #d1fae5; color: #006c49; }
    .badge.orange{ background: #ffedd5; color: #f97316; }
    .badge.gray  { background: #f3f4f6; color: #6b7280; }
    .stats-row { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; margin-top: 8px; }
    .stat-box  { background: #f9fafb; border-radius: 8px; padding: 12px; }
    .stat-box label { font-size: 10px; text-transform: uppercase; color: #9ca3af; display: block; margin-bottom: 4px; }
    .stat-box span  { font-size: 18px; font-weight: 900; color: #111; }
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
        <p style="font-size:14px;font-weight:700;margin-top:2px">{{ $restaurant->name }}</p>
        <p style="font-size:11px;color:#9ca3af;margin-top:2px">Panel Operativo — Reporte Mensual</p>
    </div>
    <div class="meta">
        <p><strong>Período:</strong> {{ now()->locale('es')->isoFormat('MMMM YYYY') }}</p>
        <p><strong>Generado:</strong> {{ now()->format('d/m/Y H:i') }}</p>
        <p><strong>Responsable:</strong> {{ auth()->user()->name }}</p>
    </div>
</div>

{{-- KPIs mensuales --}}
<h2>Resumen del Mes</h2>
<div class="kpi-grid">
    <div class="kpi green">
        <label>Ingresos del Mes</label>
        <span>${{ number_format($monthRevenue, 0, ',', '.') }}</span>
        <div class="trend">Órdenes completadas</div>
    </div>
    <div class="kpi red">
        <label>Egresos del Mes</label>
        <span>${{ number_format($monthExpenses, 0, ',', '.') }}</span>
        <div class="trend">Gastos registrados</div>
    </div>
    <div class="kpi {{ ($monthRevenue - $monthExpenses) >= 0 ? 'green' : 'red' }}">
        <label>Profit Neto</label>
        <span>${{ number_format(abs($monthRevenue - $monthExpenses), 0, ',', '.') }}</span>
        <div class="trend">{{ ($monthRevenue - $monthExpenses) >= 0 ? 'Positivo' : 'Negativo' }}</div>
    </div>
    <div class="kpi orange">
        <label>Ventas de Hoy</label>
        <span>${{ number_format($todayRevenue, 0, ',', '.') }}</span>
        <div class="trend">{{ $todayOrders }} órdenes</div>
    </div>
</div>

{{-- Indicadores operativos --}}
<h2>Indicadores Operativos</h2>
<div class="stats-row">
    <div class="stat-box">
        <label>Mesas activas / Total</label>
        <span>{{ $activeTables }} / {{ $totalTables }}</span>
    </div>
    <div class="stat-box">
        <label>Personal activo</label>
        <span>{{ $staffCount }} empleados</span>
    </div>
    <div class="stat-box">
        <label>Ítems en menú</label>
        <span>{{ $menuCount }} disponibles</span>
    </div>
    <div class="stat-box">
        <label>Órdenes hoy</label>
        <span>{{ $todayOrders }}</span>
    </div>
</div>

{{-- Últimas órdenes --}}
<h2>Últimas Órdenes</h2>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Fecha & Hora</th>
            <th>Estado</th>
            <th style="text-align:right">Total</th>
        </tr>
    </thead>
    <tbody>
        @forelse($recentOrders as $order)
        <tr>
            <td style="font-weight:700">#GT-{{ $order->id }}</td>
            <td style="color:#6b7280">{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</td>
            <td>
                @php $st = $order->status ?? 'completado'; @endphp
                <span class="badge {{ $st === 'completed' ? 'green' : ($st === 'pending' ? 'orange' : 'gray') }}">
                    {{ ucfirst($st) }}
                </span>
            </td>
            <td style="text-align:right;font-weight:700;color:#006c49">${{ number_format($order->total, 0, ',', '.') }}</td>
        </tr>
        @empty
        <tr><td colspan="4" style="text-align:center;color:#9ca3af;padding:20px">Sin órdenes este mes.</td></tr>
        @endforelse
    </tbody>
</table>

<div class="footer">
    <span>GoToEat Restaurant Management — Reporte confidencial generado automáticamente.</span>
    <span>{{ $restaurant->name }} · {{ now()->format('d/m/Y') }}</span>
</div>

<script>window.addEventListener('load', function() { window.print(); });</script>
</body>
</html>
