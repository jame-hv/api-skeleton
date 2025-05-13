<?php

declare(strict_types=1);

namespace Tests\Unit\Auth;

use App\Actions\Auth\CreateUserAction;
use App\DTOs\Auth\RegisterUser;
use App\Jobs\Auth\CreateUser;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Mockery;

describe('user registration unit tests', function (): void {
    beforeEach(function (): void {
        Event::fake();
    });

    it('can create a new user through the registration action', function (): void {
        $hashedPassword = Hash::make('password123');
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => $hashedPassword,
        ];

        $registerDto = new RegisterUser(
            name: $userData['name'],
            email: $userData['email'],
            password: $userData['password'], // Already hashed
        );

        // Mock the database manager for isolated testing
        $dbManager = Mockery::mock(DatabaseManager::class);
        $dbManager->shouldReceive('transaction')
            ->once()
            ->andReturnUsing(function ($callback, $attempts) use ($userData) {

                $user = new User([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => $userData['password'],
                ]);

                return $user;
            });

        $action = new CreateUserAction($dbManager);
        $user = $action->handle(payload: $registerDto);

        expect($user)->toBeInstanceOf(User::class)
            ->and($user->name)->toBe($userData['name'])
            ->and($user->email)->toBe($userData['email'])
            ->and($user->password)->toBe($hashedPassword);
    });

    it('dispatches a Registered event after user creation', function (): void {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ];

        // Create a properly hashed RegisterUser DTO
        $registerDto = new RegisterUser(
            name: $userData['name'],
            email: $userData['email'],
            password: $userData['password'],
        );

        // Create a user creation job with the DTO
        $job = new CreateUser(payload: $registerDto);

        // Mock the DatabaseManager that it depends on
        $dbManager = Mockery::mock(DatabaseManager::class);
        $dbManager->shouldReceive('transaction')
            ->once()
            ->withArgs(fn ($callback, $attempts = null) => is_callable($callback) && 3 === $attempts)
            ->andReturn(new User($userData));

        // Create a real instance with mocked dependency
        $action = new CreateUserAction($dbManager);

        // Handle the job with action and the event dispatcher
        $job->handle(action: $action, event: Event::getFacadeRoot());

        // Check if the event was dispatched with a User instance
        Event::assertDispatched(Registered::class, fn ($event) => $event->user instanceof User);
    });
});
