<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class UserListTest extends TestCase
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

        Permission::create(['name' => 'view.users']);
    }

    #[Test]
    public function an_admin_can_list_all_users()
    {
        User::factory()->count(3)->vehicles()->create();
        User::factory()->count(3)->people()->create();
        User::factory()->count(3)->species()->create();

        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)->get('/api/v1/users');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'type',
                    'id',
                    'attributes' => [
                        'name',
                        'email',
                        'role',
                    ],
                    'links',
                ],
            ],
        ]);

        $this->assertCount(10, $response->json('data'));

        $response->assertJsonFragment(['role' => 'vehicles']);
        $response->assertJsonFragment(['role' => 'people']);
        $response->assertJsonFragment(['role' => 'species']);
    }

    #[Test]
    public function other_roles_cannot_list_a_users()
    {
        $user = User::factory()->films()->create();

        $response = $this->actingAs($user)->get('/api/v1/users');

        $response->assertStatus(403);

        $response->assertExactJson([
            "status" => "error",
            "message" => "No tienes permiso para ver usuarios.",
            "errors" => [
                "authorization" => "Acceso denegado"
            ],
            "code" => 403
        ]);
    }
}
