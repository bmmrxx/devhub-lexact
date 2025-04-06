<?php

namespace App\Enum;

enum UserRoleEnum: string
{
    case ADMIN = 'ROLE_ADMIN';
    case MENTOR = 'ROLE_MENTOR';
    case INTERN = 'ROLE_INTERN';

    public function getBadgeClass(): string
    {
        return match ($this) {
            self::ADMIN => 'danger',
            self::MENTOR => 'warning',
            self::INTERN => 'success',
        };
    }
}
