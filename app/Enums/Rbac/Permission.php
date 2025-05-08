<?php

declare(strict_types=1);

namespace App\Enums\Rbac;

use App\Attributes\RoleAttribute;
use App\Concerns\Rbac\DescriptionAttribute;
use App\Concerns\Rbac\HasAttributes;

enum Permission: string
{
    use HasAttributes;

    // Permissions for user management

    #[RoleAttribute(roles: [Role::ADMIN])]
    #[DescriptionAttribute(description: 'Allow deleting user')]
    case DeleteUser = 'users:delete';


    #[RoleAttribute(roles: [Role::ADMIN])]
    #[DescriptionAttribute(description: 'Allow dangerous action')]
    case PerformDangerousAction = 'action:dangerous';

}
