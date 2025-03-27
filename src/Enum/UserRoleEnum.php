<?php

namespace App\Enum;

enum UserRoleEnum: string
{
    case ADMIN = 'ROLE_ADMIN';
    case MENTOR = 'ROLE_MENTOR';
    case INTERN = 'ROLE_INTERN';
}
