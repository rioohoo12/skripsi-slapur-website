<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Create basic roles
        Role::create(['name' => 'Murid']);
        Role::create(['name' => 'Guru']);
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Staff Administrasi']);
    }

    public function test_user_can_login_with_correct_role()
    {
        $user = User::factory()->create();
        $user->assignRole('Murid');

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
            'role' => 'Murid'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['access_token', 'user']);
    }

    public function test_user_cannot_login_with_incorrect_role()
    {
        $user = User::factory()->create();
        $user->assignRole('Murid');

        // Murid trying to login as Guru
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
            'role' => 'Guru'
        ]);

        $response->assertStatus(403)
                 ->assertJson(['message' => 'Anda tidak memiliki akses ke halaman role ini.']);
    }

    public function test_user_cannot_login_with_wrong_password()
    {
        $user = User::factory()->create();
        $user->assignRole('Murid');

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
            'role' => 'Murid'
        ]);

        $response->assertStatus(422);
    }
}
