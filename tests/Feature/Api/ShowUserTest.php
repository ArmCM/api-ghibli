<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ShowUserTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function an_admin_can_view_detail_of_all_users(): void
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
}
