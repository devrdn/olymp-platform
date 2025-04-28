<?php

namespace App\Http\Controllers;

use App\Services\ContestService;
use Illuminate\Http\Request;

class ContestTaskController extends Controller
{
    public function __construct(
        private ContestService $contestService
    ) {}

    public function show(int $contestId, int $taskId)
    {
        $contest = $this->contestService->fetchByIdWith($contestId, 'tasks', ['id', 'name']);
        
        $task = $contest->tasks()->findOrFail($taskId);

        return view('pages::contest.task.index', compact('contest', 'task'));
    }
}
