<?php

namespace App\Http\Controllers;

use App\Services\CodeProcessingService as ServicesCodeProcessingService;
use App\Services\ContestService;
use CodeProcessingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContestTaskController extends Controller
{
    public function __construct(
        private ContestService $contestService,
        private ServicesCodeProcessingService $codeProcessingService
    ) {}

    public function show(int $contestId, int $taskId)
    {
        $contest = $this->contestService->fetchByIdWith($contestId, 'tasks', ['id', 'name']);

        $task = $contest->tasks()->findOrFail($taskId);

        return view('pages::contest.task.index', compact('contest', 'task'));
    }

    public function submit(Request $request, int $contestId, int $taskId)
    {
        $validated = $request->validate([
            'code' => 'nullable|string',
            'file' => 'nullable|file',
            'language' => 'nullable|string',
        ]);

        // If a file is uploaded
        if ($request->hasFile('file')) {
            $fileData = $this->codeProcessingService->saveUploadedFile(
                $request->file('file'),
                $taskId,
                auth()->id() ?? 1,
                now()->format('YmdHis'),
                $validated['language'] ?? null,
                $contestId
            );

            return redirect()->route('contest.task.show', [$contestId, $taskId])
                ->with('success', 'Submission successful!');
        }

        // If code is provided as a string
        if ($validated['code']) {
            $filename = $this->codeProcessingService->saveCodeAsFile(
                $validated['code'],
                $validated['language'] ?? 'txt',
                $taskId,
                auth()->id() ?? 1,
                $contestId,
                now()->format('YmdHis')
            );

            return response()->json(['message' => 'Code saved as file', 'filename' => $filename]);
        }


        return redirect()->route('contest.task.show', [$contestId, $taskId])
            ->with('success', 'Submission successful!');
    }
}
