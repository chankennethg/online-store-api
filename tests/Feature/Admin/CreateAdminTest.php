<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreateAdminTest extends TestCase
{
    /**
     * Create Admin endpoint
     *
     * @var string
     */
    private string $apiUrl = '/api/v1/admin/create';

    /**
     * Act as admin to create an admin
     */
    public function test_admin_can_create_admin(): void
    {
        Sanctum::actingAs(
            User::factory([
                'is_admin' => true,
            ])->create(),
            ['createAdmin']
        );

        $payload = [
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'email' => fake()->email,
            'password' => 'testpassword',
            'password_confirmation' => 'testpassword',
            'address' => fake()->address,
            'phone_number' => fake()->phoneNumber,
        ];

        $response = $this->post($this->apiUrl, $payload);

        $response->assertStatus(201)
        ->assertJsonStructure([
            'data' => [
                'uuid',
                'first_name',
                'last_name',
                'email',
                'address',
                'phone_number',
                'updated_at',
                'created_at',
                'token',
            ],
        ]);
    }

    /**
     * Act as admin to create an admin
     */
    public function test_admin_can_create_admin_with_missing_fields(): void
    {
        Sanctum::actingAs(
            User::factory([
                'is_admin' => true,
            ])->create(),
            ['createAdmin']
        );

        $payload = [
            'first_name' => '',
            'last_name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
            'address' => '',
            'phone_number' => '',
        ];

        $response = $this->post($this->apiUrl, $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password', 'first_name', 'last_name', 'address', 'phone_number']);
    }

    /**
     * Act as admin to create an admin
     */
    public function test_non_admin_can_create_admin(): void
    {
        Sanctum::actingAs(
            User::factory([
                'is_admin' => false,
            ])->create(),
            ['createAdmin']
        );

        $payload = [
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'email' => fake()->email,
            'password' => 'testpassword',
            'password_confirmation' => 'testpassword',
            'address' => fake()->address,
            'phone_number' => fake()->phoneNumber,
        ];

        $response = $this->post($this->apiUrl, $payload);

        $response->assertStatus(403)
        ->assertJson([
            'message' => 'This action is unauthorized.',
        ]);
    }
}
