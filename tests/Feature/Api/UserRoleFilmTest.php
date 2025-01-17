<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserRoleFilmTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function only_an_user_with_role_films_can_query_films_endpoint()
    {
        $user = User::factory()->films()->create();

        $response = $this->actingAs($user)->get("/api/v1/films");

        $response->assertStatus(200);
    }

    #[Test]
    public function an_user_with_different_role_cannot_query_films_endpoint()
    {
        $user = User::factory()->vehicles()->create();

        $response = $this->actingAs($user)->get("/api/v1/films");

        $response->assertStatus(403);

        $response->assertExactJson([
            "status" => "error",
            "message" => "No tienes permiso para consultar peliculas.",
            "errors" => [
                "authorization" => "Acceso denegado"
            ],
            "code" => 403
        ]);
    }
}
