<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserRoleVehiclesTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function only_an_user_with_role_vehicles_can_query_vehicles_endpoint()
    {
        $user = User::factory()->vehicles()->create();

        $response = $this->actingAs($user)->get("/api/v1/vehicles");

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [],
            'status_code'
        ]);
    }

    #[Test]
    public function an_user_with_different_role_cannot_query_vehicles_endpoint()
    {
        $user = User::factory()->films()->create();

        $response = $this->actingAs($user)->get("/api/v1/vehicles");

        $response->assertStatus(403);

        $response->assertExactJson([
            "status" => "error",
            "message" => "No tienes permiso para consultar vehículos.",
            "errors" => [
                "authorization" => "Acceso denegado"
            ],
            "code" => 403
        ]);
    }

    #[Test]
    public function can_append_fields_and_limit_in_vehicles_endpoint_for_filtering()
    {
        $user = User::factory()->vehicles()->create();

        $response = $this->actingAs($user)->get("/api/v1/vehicles?fields=id,title,description,release_date&limit=3");

        $response->assertStatus(200);

        $response->assertJsonCount(3, 'data');
    }

    #[Test]
    public function id_is_required_to_query_vehicles_endpoint()
    {
        $user = User::factory()->vehicles()->create();

        $response = $this->actingAs($user)->get("/api/v1/vehicles/:id");

        $response->assertStatus(200);

        $response->assertExactJson([
            "message" => "No se encontraron resultados.",
            "status_code" => 200
        ]);
    }

    #[Test]
    public function can_find_vehicles_by_id_and_append_fields()
    {
        $user = User::factory()->vehicles()->create();

        $response = $this->actingAs($user)->get("/api/v1/vehicles/4e09b023-f650-4747-9ab9-eacf14540cfb?fields=id,name,vehicle_class");

        $response->assertStatus(200);

        $response->assertJsonCount(1, 'data');

        $response->assertExactJson([
            'status' => 'success',
            'message' => 'Vehículos encontrados',
            'data' => [
                [
                    'id' => '4e09b023-f650-4747-9ab9-eacf14540cfb',
                    'name' => 'Air Destroyer Goliath',
                    'vehicle_class' => "Airship",
                ]
            ],
            'status_code' => 200
        ]);
    }

    #[Test]
    public function displays_error_message_for_empty_query_result_vehicles_endpoint()
    {
        $user = User::factory()->vehicles()->create();

        $response = $this->actingAs($user)->get("/api/v1/vehicles/2baf70d1");

        $response->assertStatus(200);

        $response->assertExactJson([
            "message" => "No se encontraron resultados.",
            "status_code" => 200
        ]);
    }
}
