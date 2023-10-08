<?php

namespace App\Models;

use App\Enum\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $hidden = [
        'status'
    ];

    protected $fillable = [
        'name',
        'status',
    ];

    protected $casts = [
        'status' => StatusEnum::class,
    ];

    public function Books()
    {
        return $this->hasMany(Book::class)->where(['status' => StatusEnum::ON]);
    }
}
