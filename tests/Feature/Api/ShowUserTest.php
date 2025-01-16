<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ShowUserTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function an_admin_can_view_detail_of_any_user(): void
    {
        $admin = User::factory()->admin()->create();

        $films = User::factory()->films()->create([
            'name' => 'john doe',
            'email' => 'john_01@gmail.com',
        ]);

        $response = $this->actingAs($admin)->get("/api/v1/users/$films->id");

        $response->assertStatus(200);

        $response->assertExactJson([
            'data' => [
                'type' => 'user',
                'id' => $films->id,
                'attributes' => [
                    'name' => 'john doe',
                    'email' => 'john_01@gmail.com',
                    'role' => 'films',
                    'created_at' => $films->created_at,
                    'updated_at' => $films->updated_at,
                ],
                'links' => [
                    'self' => route('users.show', $films->id)
                ]
            ],
        ]);
    }

    #[Test]
    public function a_user_can_see_only_his_own_detail_information()
    {
        $userFilms = User::factory()->films()->create([
            'name' => 'john doe',
            'email' => 'john_01@gmail.com',
        ]);

        $response = $this->actingAs($userFilms)->get("/api/v1/users/$userFilms->id");

        $response->assertStatus(200);

        $response->assertExactJson([
            'data' => [
                'type' => 'user',
                'id' => $userFilms->id,
                'attributes' => [
                    'name' => 'john doe',
                    'email' => 'john_01@gmail.com',
                    'role' => 'films',
                    'created_at' => $userFilms->created_at,
                    'updated_at' => $userFilms->updated_at,
                ],
                'links' => [
                    'self' => route('users.show', $userFilms->id)
                ]
            ],
        ]);
    }

    #[Test]
    public function an_user_cannot_view_detail_about_other_users()
    {
        $userFilms = User::factory()->films()->create([
            'name' => 'john doe',
            'email' => 'john_01@gmail.com',
        ]);

        $userSpecies = User::factory()->species()->create([
            'name' => 'jane doe',
            'email' => 'jane_01@gmail.com',
        ]);

        $response = $this->actingAs($userFilms)->get("/api/v1/users/$userSpecies->id");

        $response->assertStatus(403);

        $response->assertJson([
            'status' => 'error',
            'message' => 'No tienes permiso para ver el perfil de otro usuario.',
            'errors' => [
                'authorization' => 'Acceso denegado',
            ],
            'code' => 403,
        ]);
    }
}
