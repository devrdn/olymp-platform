<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'memory_limit',
        'time_limit',
    ];

    public function task_meta()
    {
        return $this->hasOne(TaskMeta::class);
    }
}
