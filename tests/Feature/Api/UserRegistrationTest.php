<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserRegistrationTest extends TestCase
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

        Permission::create(['name' => 'view.user']);
        Permission::create(['name' => 'update.user']);
        Permission::create(['name' => 'delete.user']);

        Role::create(['name' => 'films'])->givePermissionTo([
            'view.user',
            'update.user',
            'delete.user',
        ]);
    }

    public function validAttributes(array $overrides = []): array
    {
        return array_replace_recursive([
            'name' => 'John Doe',
            'email' => 'john_doe@gmail.com',
            'password' => 'valid-password',
            'role' => 'admin',
        ], $overrides);
    }

    #[Test]
    public function an_admin_can_create_a_user()
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)->post('/api/v1/users', $this->validAttributes([
            'role' => 'films',
        ]));

        $response->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john_doe@gmail.com',
        ]);

        $user = User::where('email', 'john_doe@gmail.com')->first();
        $this->assertTrue($user->hasRole('films'));
    }
}
