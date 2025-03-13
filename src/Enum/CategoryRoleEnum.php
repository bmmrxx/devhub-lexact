<?php

namespace App\Enum;

enum CategoryRoleEnum: string
{
    case ADMIN = 'admin';
    case MENTOR = 'mentor';
    case INTERN = 'intern';
}
