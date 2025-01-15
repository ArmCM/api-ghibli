<?php

namespace Tests\Feature\Api;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /** @test */
    public function user_can_login_with_valid_credentials(): void
    {
        $response = $this->post('/api/v1/login', [
            'email' => 'armando@gmail.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200);
    }
}
