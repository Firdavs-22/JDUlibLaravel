<?php

namespace App\Models;

use App\Enum\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'title',
        'author',
        'category_id',
        'status',
    ];

    protected $hidden = [
        'status'
    ];

    protected $casts = [
        'status' => StatusEnum::class,
    ];

    public function bookSeries()
    {
        return $this->hasMany(BookSeries::class)->where('status', StatusEnum::ON);
    }
}
