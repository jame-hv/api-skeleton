<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

describe('user registration feature tests', function (): void {
    uses(RefreshDatabase::class);

    beforeEach(function (): void {
        Event::fake();
    });

    it('returns HTTP 201 created when registration is successful', function (): void {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        // Test the actual API endpoint
        $response = $this->postJson('/api/auth/register', $userData);

        $response->assertStatus(201)
            ->assertJsonPath('message', trans('auth.registering'));
    });

    // Exception cases
    it('validates required fields during registration', function (): void {
        // Test with empty data
        $response = $this->postJson('/api/auth/register', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    });

    it('validates email uniqueness during registration', function (): void {
        // Create a user first
        User::factory()->create([
            'email' => 'existing@example.com',
        ]);

        // Try to register with the same email
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Another User',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['email']);
    });
});
