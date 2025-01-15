<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function validAttributes(array $overrides = []): array
    {
        return array_replace_recursive([
            'name' => 'John Doe',
            'email' => 'john_doe@example.com',
            'password' => 'valid-password',
            'role' => 'admin',
        ], $overrides);
    }

    #[Test]
    public function an_admin_can_create_a_user()
    {
        User::factory()->admin()->create();

        $response = $this->post('/api/v1/users', $this->validAttributes([
            'role' => 'films',
        ]));

        $response->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john_doe@example.com',
        ]);

        $user = User::where('email', 'john_doe@example.com')->first();
        $this->assertTrue($user->hasRole('films'));
    }
}
