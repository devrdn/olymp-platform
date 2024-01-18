<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'time_limit',
        'memory_limit',
        'published'
    ];

    public function taskMeta(): HasOne {
        return $this->hasOne(TaskMeta::class);
    }
}
