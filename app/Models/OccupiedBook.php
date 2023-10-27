<?php

namespace App\Models;

use App\Enum\OccupiedEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OccupiedBook extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'book_series_id',
        'student_id',
        'occupied_date',
        'returned_date',
        'status',
    ];

    protected $casts = [
        'occupied_date' => 'datetime:H:i d.m.Y',
        'returned_date' => 'datetime:H:i d.m.Y',
        'status' => OccupiedEnum::class,
    ];

    protected $hidden = [
        'status',
    ];
}
