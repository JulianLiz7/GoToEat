<?php

namespace App\Http\Controllers\Admin;

use App\Domains\Auth\Models\User;
use App\Domains\Staff\Models\StaffNotification;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class AdminStaffController extends Controller
{
    // ── Helpers ───────────────────────────────────────────────────────
    private function restaurant()
    {
        $r = auth()->user()->ownedRestaurants()->first();
        abort_unless($r, 403);

        return $r;
    }

    private function employee(int $id)
    {
        $e = $this->restaurant()->employees()->find($id);
        abort_unless($e, 404, 'Empleado no encontrado.');

        return $e;
    }

    private function clearCache(): void
    {
        Cache::forget('dashboard_stats_'.$this->restaurant()->id);
    }

    // ── Index ─────────────────────────────────────────────────────────
    public function index()
    {
        $restaurant = $this->restaurant();
        $employees = $restaurant->employees()->with('user')->latest()->get();

        // Notificaciones activas (feed visible)
        $activeNotifications = StaffNotification::where('restaurant_id', $restaurant->id)
            ->active()
            ->with('sender')
            ->latest()
            ->get();

        // Historial completo (últimas 20)
        $notificationHistory = StaffNotification::where('restaurant_id', $restaurant->id)
            ->history()
            ->with('sender')
            ->take(20)
            ->get();

        return view('admin.staff', compact(
            'restaurant', 'employees', 'activeNotifications', 'notificationHistory'
        ));
    }

    // ── Create / Store empleado ───────────────────────────────────────
    public function create()
    {
        $restaurant = $this->restaurant();
        $existingUserIds = $restaurant->employees()->pluck('user_id');
        $availableUsers = User::whereNotIn('id', $existingUserIds)
            ->where('id', '!=', $restaurant->owner_id)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('admin.staff-create', compact('restaurant', 'availableUsers'));
    }

    public function store(Request $request)
    {
        // 1. Determinar el modo de registro
        $mode = $request->input('mode', 'new');

        if ($mode === 'new') {
            // Validar campos para invitar nuevo usuario
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
                'position' => ['required', 'string', 'max:100'],
                'salary' => ['nullable', 'numeric', 'min:0'],
                'hire_date' => ['nullable', 'date'],
                'status' => ['in:active,inactive,on_leave'],
                'emergency_contact' => ['nullable', 'string', 'max:255'],
                'notes' => ['nullable', 'string'],
            ]);

            // Generar un nombre de usuario único a partir del nombre
            $baseUsername = Str::slug($request->name, '.');
            $username = $baseUsername;
            $count = 1;
            while (User::where('username', $username)->exists()) {
                $username = $baseUsername.$count;
                $count++;
            }

            // Generar una contraseña temporal segura pero amigable (combinando mayúsculas, minúsculas, números y símbolos)
            $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $lowercase = 'abcdefghijklmnopqrstuvwxyz';
            $numbers = '0123456789';
            $symbols = '@#$&*!';
            $passwordText = substr(str_shuffle($uppercase), 0, 2).
                            substr(str_shuffle($lowercase), 0, 2).
                            substr(str_shuffle($numbers), 0, 2).
                            substr(str_shuffle($symbols), 0, 2);
            $passwordText = str_shuffle($passwordText);

            // Crear el usuario en la BD
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'username' => $username,
                'password' => Hash::make($passwordText),
            ]);

            // Asignar rol correspondiente según la posición
            $roleMap = [
                'chef' => 'chef',
                'mesero' => 'mesero',
                'cajero' => 'cajero',
                'manager' => 'admin',
                'bartender' => 'mesero',
                'auxiliar' => 'chef',
                'domiciliario' => 'mesero',
            ];
            $positionKey = strtolower($request->position);
            $roleName = $roleMap[$positionKey] ?? 'mesero';

            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $user->assignRole($roleName);

            $userId = $user->id;

            // Guardar credenciales en la sesión para mostrarlas en la vista de administración
            session()->flash('new_employee_credentials', [
                'name' => $user->name,
                'username' => $username,
                'password' => $passwordText,
                'position' => $request->position,
            ]);

        } else {
            // Asignar usuario existente
            $request->validate([
                'user_id' => ['required', 'exists:users,id'],
                'position' => ['required', 'string', 'max:100'],
                'salary' => ['nullable', 'numeric', 'min:0'],
                'hire_date' => ['nullable', 'date'],
                'status' => ['in:active,inactive,on_leave'],
                'emergency_contact' => ['nullable', 'string', 'max:255'],
                'notes' => ['nullable', 'string'],
            ]);

            $userId = $request->user_id;

            // Asignar el rol al usuario existente si no lo tiene
            $user = User::find($userId);
            $roleMap = [
                'chef' => 'chef',
                'mesero' => 'mesero',
                'cajero' => 'cajero',
                'manager' => 'admin',
                'bartender' => 'mesero',
                'auxiliar' => 'chef',
                'domiciliario' => 'mesero',
            ];
            $positionKey = strtolower($request->position);
            $roleName = $roleMap[$positionKey] ?? 'mesero';

            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            if (! $user->hasRole($roleName)) {
                $user->assignRole($roleName);
            }
        }

        // Crear el registro de empleado en la BD
        $this->restaurant()->employees()->create([
            'user_id' => $userId,
            'position' => $request->position,
            'salary' => $request->salary,
            'hire_date' => $request->hire_date,
            'status' => $request->status ?? 'active',
            'emergency_contact' => $request->emergency_contact,
            'notes' => $request->notes,
        ]);

        $this->clearCache();

        $successMsg = $mode === 'new'
            ? '✓ Empleado agregado al equipo exitosamente.'
            : '✓ Empleado asignado al equipo exitosamente.';

        return redirect()->route('admin.staff')->with('success', $successMsg);
    }

    // ── Update empleado ───────────────────────────────────────────────
    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'position' => ['required', 'string', 'max:100'],
            'salary' => ['nullable', 'numeric', 'min:0'],
            'hire_date' => ['nullable', 'date'],
            'status' => ['required', 'in:active,inactive,on_leave'],
            'emergency_contact' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $this->employee($id)->update($data);
        $this->clearCache();

        return redirect()->route('admin.staff')->with('success', '✓ Datos del empleado actualizados.');
    }

    // ── Destroy empleado ──────────────────────────────────────────────
    public function destroy(int $id)
    {
        $employee = $this->employee($id);
        $name = $employee->user?->name ?? 'Empleado';
        $employee->delete();
        $this->clearCache();

        return redirect()->route('admin.staff')->with('success', "✓ {$name} eliminado del equipo.");
    }

    // ── Canal de notificaciones ───────────────────────────────────────

    /** Duración en minutos según preset. */
    private function parseDuration(string $duracion): ?Carbon
    {
        return match ($duracion) {
            '1h' => now()->addHour(),
            '4h' => now()->addHours(4),
            '8h' => now()->addHours(8),
            '24h' => now()->addDay(),
            '3d' => now()->addDays(3),
            '7d' => now()->addWeek(),
            '30d' => now()->addDays(30),
            'permanente' => null,
            default => null,
        };
    }

    public function sendNotification(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'message' => ['required', 'string', 'max:1000'],
            'type' => ['required', 'in:info,aviso,urgente'],
            'duracion' => ['required', 'string'],
        ]);

        $restaurant = $this->restaurant();

        StaffNotification::create([
            'restaurant_id' => $restaurant->id,
            'sender_id' => auth()->id(),
            'title' => $data['title'],
            'message' => $data['message'],
            'type' => $data['type'],
            'expires_at' => $this->parseDuration($data['duracion']),
        ]);

        return redirect()->route('admin.staff')
            ->with('success', '✓ Notificación enviada al equipo.');
    }

    /** Archivar (ocultar del feed activo, pero conservar en historial) */
    public function archiveNotification(int $id)
    {
        $notif = StaffNotification::where('restaurant_id', $this->restaurant()->id)->findOrFail($id);
        $notif->update(['archived_at' => now()]);

        return redirect()->route('admin.staff')->with('success', '✓ Notificación archivada.');
    }
}
