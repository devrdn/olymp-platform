<?php

namespace App\DTO;

use App\Config\TaskStatus;
use App\Entity\UserSolution;

class TaskStatusRequest
{
    private int $taskId;

    private int $solutionId;

    private TaskStatus $taskStatus;

    /**
     * @param UserSolution $userSolution
     */
    public function __construct(int $taskId, int $solutionId, TaskStatus $taskStatus)
    {
        $this->solutionId = $solutionId;
        $this->taskId = $taskId;
        $this->taskStatus = $taskStatus;
    }

    /**
     * @return int
     */
    public function getTaskId(): int
    {
        return $this->taskId;
    }

    /**
     * @param int $taskId
     */
    public function setTaskId(int $taskId): void
    {
        $this->taskId = $taskId;
    }

    /**
     * @return TaskStatus
     */
    public function getTaskStatus(): TaskStatus
    {
        return $this->taskStatus;
    }

    /**
     * @param TaskStatus $taskStatus
     */
    public function setTaskStatus(TaskStatus $taskStatus): void
    {
        $this->taskStatus = $taskStatus;
    }

    /**
     * @return int
     */
    public function getSolutionId(): int
    {
        return $this->solutionId;
    }

    /**
     * @param int $solutionId
     */
    public function setSolutionId(int $solutionId): void
    {
        $this->solutionId = $solutionId;
    }
}