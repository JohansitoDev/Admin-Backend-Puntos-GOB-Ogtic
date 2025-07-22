<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected User $testUser;

    protected function setUp(): void
    {
        parent::setUp();

        
        Role::findOrCreate('admin'); 
        $this->testUser = User::factory()->create([
            'email' => 'testuser@example.com',
            'password' => Hash::make('oldpassword'), 
        ]);
        $this->testUser->assignRole('admin'); 
    }

    /** @test */
    public function an_authenticated_user_can_update_their_profile()
    {
        $this->actingAs($this->testUser, 'sanctum');

        $response = $this->putJson('/api/profile', [
            'name' => 'Updated Test User',
            'email' => 'updated_email@example.com', 
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'name' => 'Updated Test User',
                     'email' => 'updated_email@example.com',
                 ]);

        $this->assertDatabaseHas('users', [
            'id' => $this->testUser->id,
            'name' => 'Updated Test User',
            'email' => 'updated_email@example.com',
        ]);
    }

    /** @test */
    public function an_authenticated_user_can_change_their_password()
    {
        $this->actingAs($this->testUser, 'sanctum');

        $response = $this->putJson('/api/profile/password', [
            'current_password' => 'oldpassword',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Contraseña actualizada correctamente.']);

   
        $this->assertTrue(Hash::check('newpassword', $this->testUser->fresh()->password));
    }

    /** @test */
    public function password_change_fails_with_incorrect_current_password()
    {
        $this->actingAs($this->testUser, 'sanctum');

        $response = $this->putJson('/api/profile/password', [
            'current_password' => 'wrongpassword', 
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertStatus(422) 
                 ->assertJsonValidationErrors('current_password');

   
        $this->assertTrue(Hash::check('oldpassword', $this->testUser->fresh()->password));
    }

    /** @test */
    public function unauthenticated_users_cannot_update_profile_or_password()
    {
        $response = $this->putJson('/api/profile', []);
        $response->assertStatus(401);

        $response = $this->putJson('/api/profile/password', []);
        $response->assertStatus(401);
    }
}