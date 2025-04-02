<?php

namespace App\Enum;

enum UserRoleEnum: string
{
    case ADMIN = 'ROLE_ADMIN';
    case MENTOR = 'ROLE_MENTOR';
    case INTERN = 'ROLE_INTERN';

    public static function getVisibilityOptions(): array
    {
        return [
            self::ADMIN->value => 'Alleen beheerders',
            self::MENTOR->value => 'Mentors + beheerders',
            self::INTERN->value => 'Iedereen',
        ];
    }

    public function getBadgeClass(): string
    {
        return match ($this) {
            self::ADMIN => 'danger',
            self::MENTOR => 'warning',
            self::INTERN => 'success',
        };
    }
}
