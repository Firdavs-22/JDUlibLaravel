<?php

namespace App\Enum;

enum ActionEnum: string
{
    case CREATE = 'create';
    case UPDATE = 'update';
    case DELETE = 'delete';
    case OTHER = 'other';
}
