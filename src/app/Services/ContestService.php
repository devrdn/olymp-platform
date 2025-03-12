<?php

namespace App\Services;

use App\DTOs\CreateContestDTO;
use App\Models\Contest;

final class ContestService
{
    public function create(CreateContestDTO $dto)
    {
        Contest::create([
            'title' => $dto->title,
            'description' => $dto->description,
            'start_time' => $dto->startTime,
            'end_time' => $dto->endTime,
        ]);
    }
}
