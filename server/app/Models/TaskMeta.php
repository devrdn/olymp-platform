<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskMeta extends Model
{
    use HasFactory;

    protected $table = 'task_meta';

    public $fillable = [
        'task_id',
        'solved',
        'complexity',
        'source',
    ];
}
