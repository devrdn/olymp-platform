<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
    protected array $fillable = [
        'title',
        'description',
        'start_time',
        'end_time'
    ];
}
