<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GetAllFilmsTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function only_an_user_with_role_films_can_query_films_endpoint()
    {
        $user = User::factory()->films()->create();

        $response = $this->actingAs($user)->get("/api/v1/films");

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [],
            'status_code'
        ]);
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

    #[Test]
    public function can_append_fields_and_limit_in_films_endpoint_for_filtering()
    {
        $user = User::factory()->films()->create();

        $response = $this->actingAs($user)->get("/api/v1/films?fields=id,title,description,release_date&limit=3");

        $response->assertStatus(200);

        $response->assertJsonCount(3, 'data');
    }

    #[Test]
    public function id_is_required_to_query_films_endpoint()
    {
        $user = User::factory()->films()->create();

        $response = $this->actingAs($user)->get("/api/v1/films/:id");

        $response->assertStatus(200);

        $response->assertExactJson([
            "message" => "No se encontraron resultados.",
            "status_code" => 200
        ]);
    }

    #[Test]
    public function can_find_films_by_id_and_append_fields()
    {
        $user = User::factory()->films()->create();

        $response = $this->actingAs($user)->get("/api/v1/films/2baf70d1-42bb-4437-b551-e5fed5a87abe?fields=id,title,original_title");

        $response->assertStatus(200);

        $response->assertJsonCount(1, 'data');

        $response->assertExactJson([
            'status' => 'success',
            'message' => 'Peliculas encontradas',
            'data' => [
                [
                    'id' => '2baf70d1-42bb-4437-b551-e5fed5a87abe',
                    'title' => 'Castle in the Sky',
                    'original_title' => "天空の城ラピュタ",
                ]
            ],
            'status_code' => 200
        ]);
    }

    #[Test]
    public function displays_error_message_for_empty_query_result_films_endpoint()
    {
        $user = User::factory()->films()->create();

        $response = $this->actingAs($user)->get("/api/v1/films/2baf70d1");

        $response->assertStatus(200);

        $response->assertExactJson([
            "message" => "No se encontraron resultados.",
            "status_code" => 200
        ]);
    }
}
