<?php

namespace App\Enum;

enum ActionTableEnum: string
{
    case USER = 'user';
    case STUDENT = 'student';
    case BOOK = 'book';
    case CATEGORY = 'category';
    case BOOK_SERIES = 'book_series';
    case OCCUPIED_BOOK = 'occupied_book';
}
