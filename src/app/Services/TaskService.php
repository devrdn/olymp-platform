<?php

namespace App\Services;

use App\Models\Task;

final class TaskService
{
    public function fetchById(int $id): Task
    {
        return Task::findOrFail($id);
    }
}