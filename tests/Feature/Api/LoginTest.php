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

        $response->assertJson([
            'message' => 'Authenticated',
            'status_code' => 200,
        ]);

        $response->assertJsonStructure([
            'data' => [
                'token',
            ],
            'message',
            'status_code',
        ]);
    }

    #[Test]
    public function user_cannot_login_if_email_doesnt_exist(): void
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
                    "El campo correo electrónico proporcionado no existe."
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
                    "El campo correo electrónico debe ser una dirección de correo válida."
                ]
            ]
        ]);
    }

    #[Test]
    public function show_error_message_when_send_invalid_format_length_password(): void
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => 'password',
        ]);

        $response = $this->post('/api/v1/login', [
            'email' => $user->email,
            'password' => 'invalid',
        ]);

        $response->assertStatus(422);

        $response->assertExactJson([
            "message" => "Error de validación.",
            "errors" => [
                "password" => [
                    "El campo contraseña debe contener al menos 8 caracteres."
                ]
            ]
        ]);
    }

    #[Test]
    public function show_error_message_when_send_invalid_format_password(): void
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => 'password',
        ]);

        $response = $this->post('/api/v1/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401);

        $response->assertExactJson([
            "status_code" => 401,
            "message" => "Verifica que tu contraseña sea correcta.",
        ]);
    }

    #[Test]
    public function show_error_message_when_send_empty_password(): void
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => 'password',
        ]);

        $response = $this->post('/api/v1/login', [
            'email' => $user->email,
            'password' => '',
        ]);

        $response->assertStatus(422);

        $response->assertExactJson([
            "message" => "Error de validación.",
            "errors" => [
                "password" => [
                    "El campo contraseña es obligatorio."
                ]
            ]
        ]);
    }
}
