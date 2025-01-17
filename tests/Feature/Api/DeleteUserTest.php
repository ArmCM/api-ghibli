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

        $response->assertStatus(200);

        $response->assertJson([
            'message' => 'Usuario eliminado exitosamente.',
        ]);
    }
}
