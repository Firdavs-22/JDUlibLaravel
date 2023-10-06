<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enum\RoleEnum;
use App\Enum\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $timestamps = false;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'telegram',
        'phone_number',
        'password',
        'created_date',
        'role',
        'status',
    ];


    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'status',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'created_date' => 'datetime:H:i d.m.Y',
        'role' => RoleEnum::class,
        'status' => StatusEnum::class,
    ];

}
