<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Login endpoint
     *
     * @var string
     */
    private string $loginUrl = '/api/v1/admin/login';


    /**
     * A basic login test
     */
    public function test_login(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('test'),
            'is_admin' => true,
        ]);

        $data = [
            'email' => $user->email,
            'password' => 'test',
        ];

        $response = $this->json('POST', $this->loginUrl, $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'uuid',
                    'email',
                    'first_name',
                    'last_name',
                    'token',
                ],
            ]);
    }

    /**
     * Test non admin login in admin login endpoint
     */
    public function test_non_admin_login(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('test'),
            'is_admin' => false,
        ]);

        $data = [
            'email' => $user->email,
            'password' => 'test',
        ];

        $response = $this->json('POST', $this->loginUrl, $data);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    }

    /**
     * Test failed login
     *
     * @return void
     */
    public function test_login_with_invalid_credentials()
    {
        $data = [
            'email' => 'nonexistentuser@example.com',
            'password' => 'invalidpassword',
        ];

        $response = $this->json('POST', $this->loginUrl, $data);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    }

    /**
     * Login with missing fields
     *
     * @return void
     */
    public function test_login_with_missing_fields()
    {
        $data = [
            'email' => '',
            'password' => '',
        ];

        $response = $this->json('POST', $this->loginUrl, $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    }
}
