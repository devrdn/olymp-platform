<?php

namespace App\DTOs;

class UpdateContestDTO
{
    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly string $startTime,
        public readonly string $endTime,
    ) {}
}
