<?php

namespace Tests\Feature;

use App\Domains\Restaurant\Models\Restaurant;
use App\Mail\EmployeeInvitationMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminStaffTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear los roles base
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'chef', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'mesero', 'guard_name' => 'web']);
    }

    public function test_manager_can_invite_new_employee_by_email(): void
    {
        Mail::fake();

        // 1. Crear usuario administrador (manager) y su restaurante
        $manager = User::factory()->create();
        $manager->assignRole('admin');

        $restaurant = Restaurant::create([
            'owner_id' => $manager->id,
            'name' => 'Test Restaurant',
            'slug' => 'test-restaurant',
            'cuisine_type' => 'Italiana',
            'status' => 'active',
        ]);

        // 2. Hacer la petición para invitar a un nuevo empleado (chef)
        $response = $this
            ->actingAs($manager)
            ->post(route('admin.staff.store'), [
                'mode' => 'new',
                'name' => 'Pedro Pérez',
                'email' => 'pedro.perez@example.com',
                'position' => 'Chef',
                'salary' => 1500000,
                'hire_date' => '2026-05-18',
                'status' => 'active',
                'emergency_contact' => '3001234567',
                'notes' => 'Excelente chef de repostería.',
            ]);

        // 3. Verificar redirección y éxito en la base de datos
        $response->assertRedirect(route('admin.staff'));
        $this->assertDatabaseHas('users', [
            'name' => 'Pedro Pérez',
            'email' => 'pedro.perez@example.com',
        ]);

        $newEmployeeUser = \App\Domains\Auth\Models\User::where('email', 'pedro.perez@example.com')->first();
        $this->assertNotNull($newEmployeeUser);
        $this->assertEquals('pedro.perez', $newEmployeeUser->username);
        $this->assertTrue($newEmployeeUser->hasRole('chef'));

        $this->assertDatabaseHas('employees', [
            'user_id' => $newEmployeeUser->id,
            'restaurant_id' => $restaurant->id,
            'position' => 'Chef',
            'salary' => 1500000,
        ]);

        // 4. Verificar que no se envió el correo y que las credenciales están en la sesión
        Mail::assertNotSent(EmployeeInvitationMail::class);
        $this->assertTrue(session()->has('new_employee_credentials'));
        $creds = session('new_employee_credentials');
        $this->assertEquals('Pedro Pérez', $creds['name']);
        $this->assertEquals('pedro.perez', $creds['username']);
        $this->assertEquals('Chef', $creds['position']);
        $this->assertNotEmpty($creds['password']);
    }

    public function test_employee_is_routed_to_employee_dashboard_upon_login(): void
    {
        // 1. Crear manager y restaurante
        $manager = User::factory()->create();
        $restaurant = Restaurant::create([
            'owner_id' => $manager->id,
            'name' => 'Test Restaurant',
            'slug' => 'test-restaurant',
            'cuisine_type' => 'Italiana',
            'status' => 'active',
        ]);

        // 2. Crear usuario empleado (mesero) con su registro
        $employeeUser = User::factory()->create();
        $employeeUser->assignRole('mesero');

        $restaurant->employees()->create([
            'user_id' => $employeeUser->id,
            'position' => 'Mesero',
            'salary' => 1200000,
            'hire_date' => '2026-05-18',
            'status' => 'active',
        ]);

        // 3. Simular login del empleado y acceder al dashboard principal
        $response = $this
            ->actingAs($employeeUser)
            ->get('/dashboard');

        // 4. Verificar que redirige o renderiza el dashboard de empleado
        $response->assertStatus(200);
        $response->assertViewIs('empleado.dashboard');
        $response->assertViewHas('cargo', 'Mesero');
    }

    public function test_employee_can_login_using_username_and_password(): void
    {
        Role::firstOrCreate(['name' => 'mesero', 'guard_name' => 'web']);

        $user = \App\Domains\Auth\Models\User::create([
            'name' => 'Carlos Méndez',
            'email' => 'carlos.mendez@example.com',
            'username' => 'carlos.mendez',
            'password' => Hash::make('Xk29@Lm2'),
        ]);

        $response = $this->post('/login', [
            'email' => 'carlos.mendez',
            'password' => 'Xk29@Lm2',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('dashboard', absolute: false));
    }
}
