<?php

namespace App\Models;

use App\Enum\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookSeries extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'serial_id',
        'book_id',
        'status',
    ];

    protected $casts = [
        'status' => StatusEnum::class,
    ];
}
