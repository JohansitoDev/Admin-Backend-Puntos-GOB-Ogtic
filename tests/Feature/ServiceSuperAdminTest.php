<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Service;
use Spatie\Permission\Models\Role;

class ServiceSuperAdminTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected User $superAdmin;
    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        Role::findOrCreate('superadmin');
        Role::findOrCreate('admin');

        $this->superAdmin = User::factory()->create([
            'email' => 'superadmin@test.com',
        ]);
        $this->superAdmin->assignRole('superadmin');

        $this->adminUser = User::factory()->create(['email' => 'admin_test@test.com']);
        $this->adminUser->assignRole('admin');
    }

    /** @test */
    public function a_superadmin_can_create_a_service()
    {
        $this->actingAs($this->superAdmin, 'sanctum');

        $response = $this->postJson('/api/superadmin/services', [
            'name' => 'Nuevo Servicio Test',
            'description' => $this->faker->paragraph,
            'is_active' => true,
        ]);

        $response->assertStatus(201)
                 ->assertJsonFragment(['name' => 'Nuevo Servicio Test']);

        $this->assertDatabaseHas('services', ['name' => 'Nuevo Servicio Test']);
    }

    /** @test */
    public function a_superadmin_can_list_all_services()
    {
        $this->actingAs($this->superAdmin, 'sanctum');
        Service::factory()->count(3)->create();

        $response = $this->getJson('/api/superadmin/services');

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function a_superadmin_can_show_a_specific_service()
    {
        $this->actingAs($this->superAdmin, 'sanctum');
        $service = Service::factory()->create();

        $response = $this->getJson('/api/superadmin/services/' . $service->id);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => $service->name]);
    }

    /** @test */
    public function a_superadmin_can_update_a_service()
    {
        $this->actingAs($this->superAdmin, 'sanctum');
        $serviceToUpdate = Service::factory()->create(['name' => 'Old Service Name']);

        $response = $this->putJson('/api/superadmin/services/' . $serviceToUpdate->id, [
            'name' => 'Updated Service Name',
            'description' => $serviceToUpdate->description,
            'is_active' => $serviceToUpdate->is_active,
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Updated Service Name']);

        $this->assertDatabaseHas('services', ['id' => $serviceToUpdate->id, 'name' => 'Updated Service Name']);
    }

    /** @test */
    public function a_superadmin_can_delete_a_service()
    {
        $this->actingAs($this->superAdmin, 'sanctum');
        $serviceToDelete = Service::factory()->create();

        $response = $this->deleteJson('/api/superadmin/services/' . $serviceToDelete->id);

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Servicio eliminado correctamente.']);

        $this->assertDatabaseMissing('services', ['id' => $serviceToDelete->id]);
    }

    /** @test */
    public function an_admin_cannot_access_superadmin_service_routes()
    {
        $this->actingAs($this->adminUser, 'sanctum');

        $response = $this->postJson('/api/superadmin/services', []);
        $response->assertStatus(403);

        $response = $this->getJson('/api/superadmin/services');
        $response->assertStatus(403);
    }

    /** @test */
    public function unauthenticated_users_cannot_access_service_routes()
    {
        $response = $this->getJson('/api/superadmin/services');
        $response->assertStatus(401);
    }
}