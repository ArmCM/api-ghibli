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
    public function a_user_can_update_partial_detail_information()
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
}
