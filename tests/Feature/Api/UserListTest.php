<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserListTest extends TestCase
{
    use RefreshDatabase;

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

        $this->assertCount(9, $response->json('data'));

        $response->assertJsonFragment(['role' => 'vehicles']);
        $response->assertJsonFragment(['role' => 'people']);
        $response->assertJsonFragment(['role' => 'species']);
    }
}
