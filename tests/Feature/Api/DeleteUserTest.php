<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DeleteUserTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function an_admin_can_delete_any_user()
    {
        $admin = User::factory()->admin()->create();

        $user = User::factory()->films()->create();

        $response = $this->actingAs($admin)->delete("/api/v1/users/$user->id");

        $response->assertExactJson([
            'status' => 'success',
            'message' => 'Usuario eliminado exitosamente.',
            'data' => [],
            'options' => [],
            'status_code' => 200,
        ]);
    }

    #[Test]
    public function a_user_can_delete_only_his_own_record()
    {
        $user = User::factory()->films()->create();

        $response = $this->actingAs($user)->delete("/api/v1/users/$user->id");

        $response->assertStatus(200);

        $response->assertExactJson([
            'status' => 'success',
            'message' => 'Usuario eliminado exitosamente.',
            'data' => [],
            'options' => [],
            'status_code' => 200,
        ]);
    }

    #[Test]
    public function an_user_cannot_delete_other_users()
    {
        $userVehicles = User::factory()->vehicles()->create();

        $userFilms = User::factory()->films()->create();

        $response = $this->actingAs($userVehicles)->delete("/api/v1/users/$userFilms->id");

        $response->assertStatus(403);

        $response->assertJson([
            'status' => 'error',
            'message' => 'No tienes permiso para eliminar el perfil de otro usuario.',
            'errors' => [
                'authorization' => 'Acceso denegado',
            ],
            'code' => 403,
        ]);
    }
}
