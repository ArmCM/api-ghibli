<?php

namespace Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EndpointLocationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function only_an_user_with_role_location_can_query_locations_endpoint()
    {
        $user = User::factory()->locations()->create();

        $response = $this->actingAs($user)->get("/api/v1/locations");

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [],
            'status_code'
        ]);
    }

    #[Test]
    public function an_user_with_different_role_cannot_query_locations_endpoint()
    {
        $user = User::factory()->vehicles()->create();

        $response = $this->actingAs($user)->get("/api/v1/locations");

        $response->assertStatus(403);

        $response->assertExactJson([
            "status" => "error",
            "message" => "No tienes permiso para consultar locaciones.",
            "errors" => [
                "authorization" => "Acceso denegado"
            ],
            "code" => 403
        ]);
    }

    #[Test]
    public function can_append_fields_and_limit_in_locations_endpoint_for_filtering()
    {
        $user = User::factory()->locations()->create();

        $response = $this->actingAs($user)->get("/api/v1/locations?fields=name,climate,surface_water&limit=3");

        $response->assertStatus(200);

        $response->assertJsonCount(3, 'data');
    }

    #[Test]
    public function id_is_required_to_query_locations_endpoint()
    {
        $user = User::factory()->locations()->create();

        $response = $this->actingAs($user)->get("/api/v1/locations/:id");

        $response->assertStatus(200);

        $response->assertExactJson([
            "message" => "No se encontraron resultados.",
            "status_code" => 200
        ]);
    }

    #[Test]
    public function display_error_message_if_a_user_dont_has_permission_to_view_details_locations()
    {
        $user = User::factory()->vehicles()->create();

        $response = $this->actingAs($user)->get('/api/v1/locations/11014596-71b0-4b3e-b8c0-1c4b15f28b9a');

        $response->assertStatus(403);

        $response->assertExactJson([
            'status' => 'error',
            'message' => 'No tienes permiso para consultar detalle de locaciones.',
            'errors' => [
                'authorization' => 'Acceso denegado'
            ],
            'code' => 403,
        ]);
    }

    #[Test]
    public function can_find_locations_by_id_and_append_fields()
    {
        $user = User::factory()->locations()->create();

        $response = $this->actingAs($user)->get("/api/v1/locations/11014596-71b0-4b3e-b8c0-1c4b15f28b9a?fields=name,climate,surface_water");

        $response->assertStatus(200);

        $response->assertJsonCount(1, 'data');

        $response->assertExactJson([
            'status' => 'success',
            'message' => 'Recursos encontrados',
            'data' => [
                [
                    'name' => 'Irontown',
                    'climate' => 'Continental',
                    'surface_water' => "40",
                ]
            ],
            'status_code' => 200
        ]);
    }

    #[Test]
    public function displays_error_message_for_empty_query_result_locations_endpoint()
    {
        $user = User::factory()->locations()->create();

        $response = $this->actingAs($user)->get("/api/v1/locations/2baf70d1");

        $response->assertStatus(200);

        $response->assertExactJson([
            "message" => "No se encontraron resultados.",
            "status_code" => 200
        ]);
    }
}
