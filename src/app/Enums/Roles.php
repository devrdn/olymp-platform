<?php

namespace App\Enums;

enum Roles: string
{
    case GUEST = 'guest';
    case AUTHENTICATED_USER = 'authenticated_user';
    case CONTESTANT = 'contestant';
    case CONTEST_MANAGER = 'contest_manager';
    case ADMIN = 'admin';
}
