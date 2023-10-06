<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enum\ActionEnum;
use App\Enum\ActionTableEnum;

class Actions extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'action_table',
        'action',
        'describe',
        'created_date',
    ];

    protected $casts = [
        'created_date' => 'datetime:H:i d.m.Y',
        'action_table' => ActionTableEnum::class,
        'action' => ActionEnum::class,
    ];
}
