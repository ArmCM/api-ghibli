<?php

namespace Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EndpointPeopleTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function only_an_user_with_role_people_can_query_people_endpoint()
    {
        $user = User::factory()->people()->create();

        $response = $this->actingAs($user)->get("/api/v1/people");

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [],
            'status_code'
        ]);
    }

    #[Test]
    public function an_user_with_different_role_cannot_query_people_endpoint()
    {
        $user = User::factory()->vehicles()->create();

        $response = $this->actingAs($user)->get("/api/v1/people");

        $response->assertStatus(403);

        $response->assertExactJson([
            "status" => "error",
            "message" => "No tienes permiso para consultar personas.",
            "errors" => [
                "authorization" => "Acceso denegado"
            ],
            "code" => 403
        ]);
    }

    #[Test]
    public function can_append_fields_and_limit_in_people_endpoint_for_filtering()
    {
        $user = User::factory()->people()->create();

        $response = $this->actingAs($user)->get("/api/v1/people?fields=id,title,description,release_date&limit=3");

        $response->assertStatus(200);

        $response->assertJsonCount(3, 'data');
    }

    #[Test]
    public function id_is_required_to_query_people_endpoint()
    {
        $user = User::factory()->people()->create();

        $response = $this->actingAs($user)->get("/api/v1/people/:id");

        $response->assertStatus(200);

        $response->assertExactJson([
            "message" => "No se encontraron resultados.",
            "status_code" => 200
        ]);
    }

    #[Test]
    public function can_find_people_by_id_and_append_fields()
    {
        $user = User::factory()->people()->create();

        $response = $this->actingAs($user)->get("/api/v1/people/267649ac-fb1b-11eb-9a03-0242ac130003?fields=id,name,gender,eye_color");

        $response->assertStatus(200);

        $response->assertJsonCount(1, 'data');

        $response->assertExactJson([
            'status' => 'success',
            'message' => 'Personas encontradas',
            'data' => [
                [
                    'eye_color' => 'Green',
                    'gender' => 'Male',
                    'id' => '267649ac-fb1b-11eb-9a03-0242ac130003',
                    'name' => 'Haku'
                ]
            ],
            'status_code' => 200
        ]);
    }

    #[Test]
    public function displays_error_message_for_empty_query_result_people_endpoint()
    {
        $user = User::factory()->people()->create();

        $response = $this->actingAs($user)->get("/api/v1/people/2baf70d1");

        $response->assertStatus(200);

        $response->assertExactJson([
            "message" => "No se encontraron resultados.",
            "status_code" => 200
        ]);
    }
}
