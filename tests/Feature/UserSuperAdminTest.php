<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\PuntoGOB; 
use Spatie\Permission\Models\Role;

class UserSuperAdminTest extends TestCase
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

   
        $this->adminUser = User::factory()->create([
            'email' => 'admin_test@test.com',
            'punto_gob_id' => PuntoGOB::factory()->create()->id, 
        ]);
        $this->adminUser->assignRole('admin');
    }

    /** @test */
    public function a_superadmin_can_create_a_new_admin_user()
    {
        $this->actingAs($this->superAdmin, 'sanctum');

        $response = $this->postJson('/api/superadmin/users', [
            'name' => 'Nuevo Admin',
            'email' => 'newadmin@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'admin', 
            'punto_gob_id' => $this->superAdmin->punto_gob_id, 
        ]);

        $response->assertStatus(201)
                 ->assertJsonFragment(['email' => 'newadmin@example.com']);

        $this->assertDatabaseHas('users', ['email' => 'newadmin@example.com']);
        $newUser = User::where('email', 'newadmin@example.com')->first();
        $this->assertTrue($newUser->hasRole('admin'));
    }

    /** @test */
    public function a_superadmin_can_list_all_users()
    {
        $this->actingAs($this->superAdmin, 'sanctum');
        User::factory()->count(5)->create(); 

        $response = $this->getJson('/api/superadmin/users');

        $response->assertStatus(200)
                 ->assertJsonCount(6, 'data'); 
    }

    /** @test */
    public function a_superadmin_can_show_a_specific_user()
    {
        $this->actingAs($this->superAdmin, 'sanctum');
        $user = User::factory()->create();

        $response = $this->getJson('/api/superadmin/users/' . $user->id);

        $response->assertStatus(200)
                 ->assertJsonFragment(['email' => $user->email]);
    }

    /** @test */
    public function a_superadmin_can_update_a_user()
    {
        $this->actingAs($this->superAdmin, 'sanctum');
        $userToUpdate = User::factory()->create(['name' => 'Old Name']);

        $response = $this->putJson('/api/superadmin/users/' . $userToUpdate->id, [
            'name' => 'Updated Name',
            'email' => $userToUpdate->email,
            'role' => 'admin', 
            'punto_gob_id' => $userToUpdate->punto_gob_id,
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Updated Name']);

        $this->assertDatabaseHas('users', ['id' => $userToUpdate->id, 'name' => 'Updated Name']);
    }

    /** @test */
    public function a_superadmin_can_delete_a_user()
    {
        $this->actingAs($this->superAdmin, 'sanctum');
        $userToDelete = User::factory()->create();

        $response = $this->deleteJson('/api/superadmin/users/' . $userToDelete->id);

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Usuario eliminado correctamente.']);

        $this->assertDatabaseMissing('users', ['id' => $userToDelete->id]);
    }

    /** @test */
    public function an_admin_cannot_access_superadmin_user_management_routes()
    {
        $this->actingAs($this->adminUser, 'sanctum'); 

        $response = $this->postJson('/api/superadmin/users', []);
        $response->assertStatus(403); 

        $response = $this->getJson('/api/superadmin/users');
        $response->assertStatus(403);

        $user = User::factory()->create();
        $response = $this->putJson('/api/superadmin/users/' . $user->id, []);
        $response->assertStatus(403);

        $response = $this->deleteJson('/api/superadmin/users/' . $user->id);
        $response->assertStatus(403);
    }

    /** @test */
    public function unauthenticated_users_cannot_access_user_management_routes()
    {
        $response = $this->postJson('/api/superadmin/users', []);
        $response->assertStatus(401); 

        $response = $this->getJson('/api/superadmin/users');
        $response->assertStatus(401);
    }
}