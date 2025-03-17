<?php

namespace App\Services;

use App\DTOs\CreateContestDTO;
use App\DTOs\UpdateContestDTO;
use App\Models\Contest;

final class ContestService
{
    public function fetchById(int $id): Contest
    {
        return Contest::findOrFail($id);
    }

    public function update(int $id, UpdateContestDTO $dto)
    {
        return $this->fetchById($id)->update([
            'title' => $dto->title,
            'description' => $dto->description,
            'start_time' => $dto->startTime,
            'end_time' => $dto->endTime,
        ]);
    }

    public function create(CreateContestDTO $dto)
    {
        return Contest::create([
            'title' => $dto->title,
            'description' => $dto->description,
            'start_time' => $dto->startTime,
            'end_time' => $dto->endTime,
        ]);
    }
}
