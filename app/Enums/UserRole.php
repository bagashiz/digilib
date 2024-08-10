<?php

namespace App\Enums;

/**
* Enum for user roles
*
* @return string
*/
enum UserRole: string
{
    case ADMIN = 'admin';
    case MEMBER = 'member';
}
