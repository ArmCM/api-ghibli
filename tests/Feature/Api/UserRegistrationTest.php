<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
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

    #[DataProvider('userRegistrationValidationScenarios')]
    public function test_fields_to_validate_a_user($field, $input, $message)
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)->post('/api/v1/users', $this->validAttributes([
            $field => $input
        ]));

        $response->assertStatus(422);

        $response->assertExactJson([
            'message' => 'Error de validación.',
            'errors' => [
                $field => [
                    $message,
                ]
            ]
        ]);
    }

    public static function userRegistrationValidationScenarios(): array
    {
        return [
            ['name', '', 'El campo nombre es obligatorio.'],
            ['name', '~/$$#', 'El formato del campo nombre es inválido.'],
            ['name', 'a', 'El campo nombre debe contener entre 2 y 75 caracteres.'],
            ['email', '', 'El campo correo electrónico debe ser una dirección de correo válida.'],
            ['email', 'invalid-email', 'El campo correo electrónico debe ser una dirección de correo válida.'],
            ['email', 'john@invalid-dns.com', 'El campo correo electrónico debe ser una dirección de correo válida.'],
            ['password', '', 'El campo contraseña es obligatorio.'],
            ['password', 'asdf', 'El campo contraseña debe contener entre 8 y 20 caracteres.'],
            ['role', '', 'El campo role es obligatorio.'],
            ['role', 'invalid-role', 'El campo role es inválido.'],
        ];
    }
}
