<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Institution;
use Spatie\Permission\Models\Role;

class InstitutionSuperAdminTest extends TestCase
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
    public function a_superadmin_can_create_an_institution()
    {
        $this->actingAs($this->superAdmin, 'sanctum');

        $response = $this->postJson('/api/superadmin/institutions', [
            'name' => 'Nueva Institución Test',
            'acronym' => 'NIT',
            'description' => $this->faker->paragraph,
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'website' => $this->faker->url,
            'is_active' => true,
        ]);

        $response->assertStatus(201)
                 ->assertJsonFragment(['name' => 'Nueva Institución Test']);

        $this->assertDatabaseHas('institutions', ['name' => 'Nueva Institución Test']);
    }

    /** @test */
    public function a_superadmin_can_list_all_institutions()
    {
        $this->actingAs($this->superAdmin, 'sanctum');
        Institution::factory()->count(3)->create();

        $response = $this->getJson('/api/superadmin/institutions');

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function a_superadmin_can_show_a_specific_institution()
    {
        $this->actingAs($this->superAdmin, 'sanctum');
        $institution = Institution::factory()->create();

        $response = $this->getJson('/api/superadmin/institutions/' . $institution->id);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => $institution->name]);
    }

    /** @test */
    public function a_superadmin_can_update_an_institution()
    {
        $this->actingAs($this->superAdmin, 'sanctum');
        $institutionToUpdate = Institution::factory()->create(['name' => 'Old Institution Name']);

        $response = $this->putJson('/api/superadmin/institutions/' . $institutionToUpdate->id, [
            'name' => 'Updated Institution Name',
            'acronym' => $institutionToUpdate->acronym,
            'description' => $institutionToUpdate->description,
            'address' => $institutionToUpdate->address,
            'phone' => $institutionToUpdate->phone,
            'email' => $institutionToUpdate->email,
            'website' => $institutionToUpdate->website,
            'is_active' => $institutionToUpdate->is_active,
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Updated Institution Name']);

        $this->assertDatabaseHas('institutions', ['id' => $institutionToUpdate->id, 'name' => 'Updated Institution Name']);
    }

    /** @test */
    public function a_superadmin_can_delete_an_institution()
    {
        $this->actingAs($this->superAdmin, 'sanctum');
        $institutionToDelete = Institution::factory()->create();

        $response = $this->deleteJson('/api/superadmin/institutions/' . $institutionToDelete->id);

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Institución eliminada correctamente.']);

        $this->assertDatabaseMissing('institutions', ['id' => $institutionToDelete->id]);
    }

    /** @test */
    public function an_admin_cannot_access_superadmin_institution_routes()
    {
        $this->actingAs($this->adminUser, 'sanctum');

        $response = $this->postJson('/api/superadmin/institutions', []);
        $response->assertStatus(403);

        $response = $this->getJson('/api/superadmin/institutions');
        $response->assertStatus(403);
    }

    /** @test */
    public function unauthenticated_users_cannot_access_institution_routes()
    {
        $response = $this->getJson('/api/superadmin/institutions');
        $response->assertStatus(401);
    }
}