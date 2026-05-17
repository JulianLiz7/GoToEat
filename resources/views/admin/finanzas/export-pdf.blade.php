<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <title>Reporte Financiero — {{ $restaurant->name }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #1a1a1a; background: #fff; padding: 32px; }
        h1 { font-size: 22px; font-weight: 900; color: #f97316; margin-bottom: 2px; }
        h2 { font-size: 14px; font-weight: 700; margin: 24px 0 10px; color: #0b1c30; border-bottom: 2px solid #f97316; padding-bottom: 4px; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 28px; }
        .meta { font-size: 11px; color: #666; line-height: 1.6; }
        .kpi-row { display: flex; gap: 16px; margin-bottom: 24px; }
        .kpi { flex: 1; background: #f8f9ff; border-radius: 8px; padding: 14px; border-left: 4px solid #f97316; }
        .kpi.green { border-left-color: #006c49; }
        .kpi.red { border-left-color: #ba1a1a; }
        .kpi label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #8c7164; display: block; margin-bottom: 4px; }
        .kpi span { font-size: 20px; font-weight: 900; }
        .kpi.green span { color: #006c49; }
        .kpi.red span { color: #ba1a1a; }
        table { width: 100%; border-collapse: collapse; font-size: 11px; }
        thead tr { background: #f8f9ff; }
        th { padding: 8px 12px; text-align: left; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: #8c7164; }
        td { padding: 8px 12px; border-bottom: 1px solid #f0f0f0; }
        tr:hover td { background: #fff8f5; }
        .total-row td { font-weight: 900; border-top: 2px solid #f97316; background: #fff8f5; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 20px; font-size: 10px; font-weight: 700; }
        .badge.green { background: #e8f5e9; color: #006c49; }
        .badge.red   { background: #fdecea; color: #ba1a1a; }
        .footer { margin-top: 40px; padding-top: 16px; border-top: 1px solid #e0e0e0; font-size: 10px; color: #999; display: flex; justify-content: space-between; }
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
        <p style="font-size:13px;font-weight:700;color:#0b1c30;margin-top:2px">{{ $restaurant->name }}</p>
        <p class="meta">Reporte Financiero — {{ now()->locale('es')->isoFormat('MMMM YYYY') }}</p>
    </div>
    <div class="meta" style="text-align:right">
        <p><strong>Generado:</strong> {{ now()->format('d/m/Y H:i') }}</p>
        <p><strong>Usuario:</strong> {{ auth()->user()->name }}</p>
    </div>
</div>

{{-- KPIs --}}
<div class="kpi-row">
    <div class="kpi green">
        <label>Ingresos del Mes</label>
        <span>${{ number_format($monthRevenue, 0, ',', '.') }}</span>
    </div>
    <div class="kpi red">
        <label>Egresos del Mes</label>
        <span>${{ number_format($monthExpenses, 0, ',', '.') }}</span>
    </div>
    <div class="kpi {{ $netProfit >= 0 ? 'green' : 'red' }}">
        <label>Profit Neto</label>
        <span>${{ number_format(abs($netProfit), 0, ',', '.') }}</span>
    </div>
</div>

{{-- Ingresos --}}
<h2>Ingresos — Órdenes del Mes</h2>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Fecha</th>
            <th>Estado</th>
            <th style="text-align:right">Monto</th>
        </tr>
    </thead>
    <tbody>
        @forelse($orders as $order)
        <tr>
            <td>#GT-{{ $order->id }}</td>
            <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</td>
            <td><span class="badge green">{{ $order->status ?? 'completado' }}</span></td>
            <td style="text-align:right;font-weight:700;color:#006c49">${{ number_format($order->total, 0, ',', '.') }}</td>
        </tr>
        @empty
        <tr><td colspan="4" style="text-align:center;color:#999;padding:20px">Sin órdenes este mes.</td></tr>
        @endforelse
        <tr class="total-row">
            <td colspan="3">TOTAL INGRESOS</td>
            <td style="text-align:right">${{ number_format($monthRevenue, 0, ',', '.') }}</td>
        </tr>
    </tbody>
</table>

{{-- Egresos --}}
<h2>Egresos del Mes</h2>
<table>
    <thead>
        <tr>
            <th>Concepto</th>
            <th>Fecha</th>
            <th>Categoría</th>
            <th style="text-align:right">Monto</th>
        </tr>
    </thead>
    <tbody>
        @forelse($expenses as $expense)
        <tr>
            <td>{{ $expense->name }}</td>
            <td>{{ \Carbon\Carbon::parse($expense->expense_date)->format('d/m/Y') }}</td>
            <td><span class="badge">{{ $expense->category ?? 'Operativos' }}</span></td>
            <td style="text-align:right;font-weight:700;color:#ba1a1a">${{ number_format($expense->amount, 0, ',', '.') }}</td>
        </tr>
        @empty
        <tr><td colspan="4" style="text-align:center;color:#999;padding:20px">Sin egresos este mes.</td></tr>
        @endforelse
        <tr class="total-row">
            <td colspan="3">TOTAL EGRESOS</td>
            <td style="text-align:right">${{ number_format($monthExpenses, 0, ',', '.') }}</td>
        </tr>
    </tbody>
</table>

{{-- Propinas --}}
@if($tipLiquidation->isNotEmpty())
<h2>Liquidación de Propinas — Ley 1935/2018</h2>
<table>
    <thead>
        <tr>
            <th>Empleado</th>
            <th>Cargo</th>
            <th style="text-align:right">Total Propinas</th>
            <th style="text-align:right">Efectivo</th>
            <th style="text-align:right">Por transferir</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tipLiquidation as $row)
        <tr>
            <td>{{ $row->employee_name }}</td>
            <td>{{ $row->position ?? '—' }}</td>
            <td style="text-align:right;font-weight:700">${{ number_format($row->total_tips, 0) }}</td>
            <td style="text-align:right;color:#006c49">${{ number_format($row->cash_tips, 0) }}</td>
            <td style="text-align:right;{{ $row->transfer_pending > 0 ? 'color:#b45309;font-weight:700' : '' }}">
                ${{ number_format($row->transfer_pending, 0) }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

<div class="footer">
    <span>GoToEat Restaurant Management — Reporte confidencial generado automáticamente.</span>
    <span>{{ $restaurant->name }} · {{ now()->format('d/m/Y') }}</span>
</div>

<script>window.addEventListener('load', () => window.print());</script>
</body>
</html>
