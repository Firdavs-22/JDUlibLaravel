<?php

namespace App\Models;

use App\Enum\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'student_id',
        'first_name',
        'last_name',
        'email',
        'status',
    ];

    protected $casts = [
        'status' => StatusEnum::class,
    ];
}
