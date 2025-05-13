<?php

declare(strict_types=1);

namespace Tests\Unit\Auth;

use App\Enums\Rbac\Permission;
use App\Enums\Rbac\Role;
use App\Models\User;

describe('authorization tests', function (): void {
    test('user with Admin role has permission for DeleteUser', function (): void {
        $user = new User(['role' => Role::ADMIN]);

        $permission = Permission::DeleteUser;

        expect($user->hasPermission($permission))->toBeTrue();
    });


    test('user with Admin role has permission for PerformDangerousAction', function (): void {
        $user = new User(['role' => Role::ADMIN]);

        $permission = Permission::PerformDangerousAction;

        expect($user->hasPermission($permission))->toBeTrue();
    });

    test('user with User role does not have permission for PerformDangerousAction', function (): void {
        $user = new User(['role' => Role::USER]);

        $permission = Permission::PerformDangerousAction;

        expect($user->hasPermission($permission))->toBeFalse();
    });
});
