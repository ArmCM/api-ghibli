<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UpdateUserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Set up the test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'species']);
    }

    #[Test]
    public function an_admin_can_update_information_of_any_user()
    {
        $admin = User::factory()->admin()->create();

        $user = User::factory()->films()->create([
            'name' => 'john doe',
            'email' => 'john_doe@gmail.com',
        ]);

        $response = $this->actingAs($admin)->patch("/api/v1/users/$user->id", [
            'name' => 'jane doe',
            'email' => 'jane_doe@gmail.com',
            'password' => 'new-password',
            'role' => 'species',
        ]);

        $response->assertStatus(201);

        $response->assertJson([
            'message' => 'Usuario actualizado exitosamente.',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'jane doe',
            'email' => 'jane_doe@gmail.com',
        ]);
    }

    #[Test]
    public function a_user_can_update_only_his_own_detail_information()
    {
        $user = User::factory()->films()->create([
            'name' => 'john doe',
            'email' => 'john_01@gmail.com',
        ]);

        $response = $this->actingAs($user)->patch("/api/v1/users/$user->id", [
            'name' => 'jane doe',
            'email' => 'jane_doe@gmail.com',
            'password' => 'new-password',
            'role' => 'species',
        ]);

        $response->assertStatus(201);

        $response->assertJson([
            'message' => 'Usuario actualizado exitosamente.',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'jane doe',
            'email' => 'jane_doe@gmail.com',
        ]);
    }

    #[Test]
    public function a_user_can_update_partial_information()
    {
        $user = User::factory()->films()->create([
            'name' => 'john doe',
            'email' => 'john_01@gmail.com',
        ]);

        $response = $this->actingAs($user)->patch("/api/v1/users/$user->id", [
            'name' => 'jane doe',
        ]);

        $response->assertStatus(201);

        $response->assertJson([
            'message' => 'Usuario actualizado exitosamente.',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'jane doe',
            'email' => 'john_01@gmail.com',
        ]);
    }

    #[Test]
    public function an_user_cannot_update_detail_the_other_users()
    {
        $userFilms = User::factory()->films()->create([
            'name' => 'john doe',
            'email' => 'john_01@gmail.com',
        ]);

        $userVehicle = User::factory()->vehicles()->create([
            'name' => 'jane doe',
            'email' => 'jane_01@gmail.com',
        ]);

        $response = $this->actingAs($userFilms)->patch("/api/v1/users/$userVehicle->id", [
            'name' => 'Adam Doe',
        ]);

        $response->assertStatus(403);

        $response->assertJson([
            'status' => 'error',
            'message' => 'No tienes permiso para actualizar el perfil de otro usuario.',
            'errors' => [
                'authorization' => 'Acceso denegado',
            ],
            'code' => 403,
        ]);
    }
}
