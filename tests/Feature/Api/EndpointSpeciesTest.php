<?php

namespace Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EndpointSpeciesTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function only_an_user_with_role_location_can_query_species_endpoint()
    {
        $user = User::factory()->species()->create();

        $response = $this->actingAs($user)->get("/api/v1/species");

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [],
            'status_code'
        ]);
    }

    #[Test]
    public function an_user_with_different_role_cannot_query_species_endpoint()
    {
        $user = User::factory()->vehicles()->create();

        $response = $this->actingAs($user)->get("/api/v1/species");

        $response->assertStatus(403);

        $response->assertExactJson([
            "status" => "error",
            "message" => "No tienes permiso para consultar especies.",
            "errors" => [
                "authorization" => "Acceso denegado"
            ],
            "code" => 403
        ]);
    }

    #[Test]
    public function can_append_fields_and_limit_in_species_endpoint_for_filtering()
    {
        $user = User::factory()->species()->create();

        $response = $this->actingAs($user)->get("/api/v1/species?fields=name,climate,surface_water&limit=3");

        $response->assertStatus(200);

        $response->assertJsonCount(3, 'data');
    }

    #[Test]
    public function id_is_required_to_query_species_endpoint()
    {
        $user = User::factory()->species()->create();

        $response = $this->actingAs($user)->get("/api/v1/species/:id");

        $response->assertStatus(200);

        $response->assertExactJson([
            "message" => "No se encontraron resultados.",
            "status_code" => 200
        ]);
    }

    #[Test]
    public function can_find_species_by_id_and_append_fields()
    {
        $user = User::factory()->species()->create();

        $response = $this->actingAs($user)->get("/api/v1/species/af3910a6-429f-4c74-9ad5-dfe1c4aa04f2?fields=name,classification");

        $response->assertStatus(200);

        $response->assertJsonCount(1, 'data');

        $response->assertExactJson([
            'status' => 'success',
            'message' => 'Recursos encontrados',
            'data' => [
                [
                    'name' => 'Human',
                    'classification' => 'Mammal',
                ]
            ],
            'status_code' => 200
        ]);
    }

    #[Test]
    public function displays_error_message_for_empty_query_result_species_endpoint()
    {
        $user = User::factory()->species()->create();

        $response = $this->actingAs($user)->get("/api/v1/species/2baf70d1");

        $response->assertStatus(200);

        $response->assertExactJson([
            "message" => "No se encontraron resultados.",
            "status_code" => 200
        ]);
    }
}
