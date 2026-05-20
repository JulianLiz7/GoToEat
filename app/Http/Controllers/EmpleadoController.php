<?php

namespace App\Http\Controllers;

use App\Domains\Staff\Models\Employee;
use App\Domains\Staff\Models\StaffNotification;
use App\Domains\Reservations\Models\Reservation;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    private function getEmployee()
    {
        return Employee::where('user_id', auth()->id())
            ->with('restaurant')
            ->first();
    }

    public function dashboard()
    {
        $user     = auth()->user();
        $employee = $this->getEmployee();

        if (! $employee) {
            return redirect()->route('dashboard');
        }

        $cargo      = $employee->position ?? 'Empleado';
        $badgeId    = 'GE-' . str_pad($employee->id, 6, '0', STR_PAD_LEFT);
        $fechaIngreso = $employee->hire_date?->locale('es')->isoFormat('D MMM YYYY') ?? 'No registrada';
        $salarioBase  = number_format($employee->salary ?? 0, 0, ',', '.');

        $horasHoy        = '8h';
        $horasTarget     = '8h';
        $horasPct        = 100;
        $horasExtrasSemana = '0h';
        $horasSemana     = '40h';

        $shifts = $employee->shifts ?? [];
        $turnos = [];
        if (! empty($shifts)) {
            foreach (array_slice($shifts, 0, 3) as $s) {
                $turnos[] = [
                    'dia'  => $s['dia']  ?? ($s['day']  ?? 'Turno'),
                    'hora' => $s['hora'] ?? ($s['time'] ?? ''),
                ];
            }
        }
        if (empty($turnos)) {
            $turnos = [
                ['dia' => 'Lunes — Viernes', 'hora' => '08:00 AM — 04:00 PM'],
                ['dia' => 'Sábado', 'hora' => '09:00 AM — 02:00 PM'],
            ];
        }

        $notificaciones = StaffNotification::where('restaurant_id', $employee->restaurant_id)
            ->active()->latest()->take(5)->get()
            ->map(fn ($n) => [
                'titulo'  => $n->title,
                'mensaje' => $n->message,
                'icono'   => $n->type === 'urgente' ? 'alarm' : ($n->type === 'aviso' ? 'groups' : 'check_circle'),
                'color'   => $n->type === 'urgente' ? 'orange' : ($n->type === 'aviso' ? 'blue' : 'emerald'),
            ])->toArray();

        if (empty($notificaciones)) {
            $notificaciones = [[
                'titulo'  => '¡Bienvenido a tu Panel de Empleado!',
                'mensaje' => 'Aquí verás tus turnos, reservas asignadas y comunicaciones del equipo.',
                'icono'   => 'check_circle',
                'color'   => 'emerald',
            ]];
        }

        $todayReservations = Reservation::with(['user', 'table'])
            ->where('restaurant_id', $employee->restaurant_id)
            ->where(fn ($q) => $q->where('waiter_id', $employee->id)->orWhereNull('waiter_id'))
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('reservation_date', '>=', now()->toDateString())
            ->orderBy('reservation_date')->orderBy('reservation_time')
            ->take(8)->get();

        return view('empleado.dashboard', compact(
            'cargo', 'badgeId', 'fechaIngreso', 'salarioBase',
            'horasHoy', 'horasTarget', 'horasPct', 'horasExtrasSemana', 'horasSemana',
            'turnos', 'notificaciones', 'employee', 'todayReservations'
        ));
    }

    public function turnos()
    {
        $employee = $this->getEmployee();
        if (! $employee) return redirect()->route('dashboard');

        $now        = now();
        $mesActual  = ucfirst($now->locale('es')->isoFormat('MMMM YYYY'));
        $diasMes    = $now->daysInMonth;
        $diaActual  = $now->day;
        $dow        = (int) $now->copy()->startOfMonth()->format('N'); // 1=Mon…7=Sun
        $offsetDias = $dow - 1;

        $shifts = $employee->shifts ?? [];
        $diasConTurno = [];
        $proximosTurnos = [];

        if (! empty($shifts)) {
            foreach ($shifts as $s) {
                $dia   = $s['dia']  ?? ($s['day']  ?? '');
                $hora  = $s['hora'] ?? ($s['time'] ?? '');
                $area  = $s['area'] ?? 'Área general';
                $tipo  = $s['tipo'] ?? 'mañana';

                $proximosTurnos[] = [
                    'dia'      => $dia,
                    'hora'     => $hora,
                    'duracion' => $s['duracion'] ?? '8h',
                    'area'     => $area,
                    'tipo'     => $tipo,
                ];
            }
            // Marcar días del mes con turno (simplificado: lunes a viernes)
            for ($d = 1; $d <= $diasMes; $d++) {
                $fecha = $now->copy()->startOfMonth()->addDays($d - 1);
                if ($fecha->isWeekday()) {
                    $diasConTurno[] = $d;
                }
            }
        } else {
            $proximosTurnos = [
                ['dia' => 'Lunes — Viernes', 'hora' => '08:00 — 16:00', 'duracion' => '8h', 'area' => 'Área general', 'tipo' => 'mañana'],
            ];
            for ($d = 1; $d <= $diasMes; $d++) {
                if ($now->copy()->startOfMonth()->addDays($d - 1)->isWeekday()) {
                    $diasConTurno[] = $d;
                }
            }
        }

        $horaInicioTurno = ! empty($proximosTurnos) ? explode(' — ', $proximosTurnos[0]['hora'] ?? '08:00')[0] : '08:00';
        $areaTurno       = ! empty($proximosTurnos) ? ($proximosTurnos[0]['area'] ?? 'Área general') : 'Área general';

        $horasMes    = count($diasConTurno) * 8;
        $horasMesPct = min(100, round(($diaActual / $diasMes) * 100));
        $asistencia  = '100.0';
        $desempeno   = '5.0';

        $cargo = $employee->position ?? 'Empleado';

        return view('empleado.turnos', compact(
            'employee', 'cargo', 'mesActual', 'diasMes', 'diaActual', 'offsetDias',
            'diasConTurno', 'proximosTurnos', 'horaInicioTurno', 'areaTurno',
            'horasMes', 'horasMesPct', 'asistencia', 'desempeno'
        ));
    }

    public function pagos()
    {
        $employee = $this->getEmployee();
        if (! $employee) return redirect()->route('dashboard');

        $salario    = (float) ($employee->salary ?? 0);
        $hoy        = now();
        $anioActual = $hoy->year;

        $salarioBase      = '$' . number_format($salario, 0, ',', '.');
        $proximoPago      = '$' . number_format($salario, 0, ',', '.');
        $fechaProximoPago = $hoy->copy()->endOfMonth()->locale('es')->isoFormat('D [de] MMMM[,] YYYY');

        // Meses trabajados en este año (desde hire_date o inicio del año)
        $inicioAnio      = $hoy->copy()->startOfYear();
        $inicioTrabajo   = $employee->hire_date ? max($employee->hire_date, $inicioAnio) : $inicioAnio;
        $mesesTrabajados = max(1, (int) $inicioTrabajo->diffInMonths($hoy) + 1);
        $acumuladoAnual  = '$' . number_format($salario * $mesesTrabajados, 0, ',', '.');

        $deduccionPct = 0.08; // 8% simplificado (salud + pensión)
        $recibos = collect();
        $meses   = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

        for ($i = 0; $i < min(12, $mesesTrabajados); $i++) {
            $fecha      = $hoy->copy()->subMonths($i);
            $deduccion  = round($salario * $deduccionPct);
            $neto       = $salario - $deduccion;
            $recibos->push([
                'periodo'     => $meses[$fecha->month - 1] . ' ' . $fecha->year,
                'bruto'       => '$' . number_format($salario, 0, ',', '.'),
                'deducciones' => '$' . number_format($deduccion, 0, ',', '.'),
                'neto'        => '$' . number_format($neto, 0, ',', '.'),
                'estado'      => $i === 0 ? 'Pendiente' : 'Pagado',
            ]);
        }

        $totalPeriodos = $recibos->count();
        $cargo = $employee->position ?? 'Empleado';

        return view('empleado.pagos', compact(
            'employee', 'cargo', 'salarioBase', 'proximoPago', 'fechaProximoPago',
            'acumuladoAnual', 'anioActual', 'recibos', 'totalPeriodos'
        ));
    }

    public function perfil()
    {
        $employee = $this->getEmployee();
        if (! $employee) return redirect()->route('dashboard');

        $user        = auth()->user();
        $cargo       = $employee->position ?? 'Empleado';
        $employeeId  = 'GE-' . str_pad($employee->id, 6, '0', STR_PAD_LEFT);
        $fechaIngreso = $employee->hire_date?->locale('es')->isoFormat('D MMM YYYY') ?? 'No registrada';
        $telefono    = $user->phone ?? $employee->emergency_contact ?? 'No registrado';
        $sede        = $employee->restaurant->name ?? 'No registrada';
        $salario     = number_format($employee->salary ?? 0, 0, ',', '.');

        return view('empleado.perfil', compact(
            'employee', 'cargo', 'employeeId', 'fechaIngreso',
            'telefono', 'sede', 'salario'
        ));
    }

    public function perfilUpdate(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        auth()->user()->update($validated);

        return back()->with('success', 'Perfil actualizado correctamente.');
    }
}
