<?php

namespace App\Models;

use App\Enums\ContestStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_time',
        'end_time',
        'status',
    ];

    protected $cast = [
        'status' => ContestStatus::class,
    ];
}
