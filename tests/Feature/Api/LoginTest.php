<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => 'password',
        ]);

        $response = $this->post('/api/v1/login', [
            'email' => 'john@example.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertStatus(200);
    }

    #[Test]
    public function user_cannot_login_is_email_doesnt_exist(): void
    {
        $response = $this->post('/api/v1/login', [
            'email' => 'john@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(422);

        $response->assertExactJson([
            "message" => "Error de validación.",
            "errors" => [
                "email" => [
                    "El email proporcionado no está registrado."
                ]
            ]
        ]);
    }

    #[Test]
    public function show_error_message_when_send_invalid_format_email(): void
    {
        $response = $this->post('/api/v1/login', [
            'email' => 'invalid-email',
            'password' => 'password',
        ]);

        $response->assertStatus(422);

        $response->assertExactJson([
            "message" => "Error de validación.",
            "errors" => [
                "email" => [
                    "El formato del email no es válido."
                ]
            ]
        ]);
    }
}
