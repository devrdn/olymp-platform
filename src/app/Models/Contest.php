<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
    protected $fillable = [
        'title',
        'description',
        'start_time',
        'end_time'
    ];

    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'contest_task');
    }
}
