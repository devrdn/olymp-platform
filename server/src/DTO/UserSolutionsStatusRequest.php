<?php

namespace App\DTO;

use App\Config\SolutionStatus;
use App\Entity\UserSolution;

class UserSolutionsStatusRequest
{
    private int $taskId;

    private int $solutionId;

    private SolutionStatus $solutionStatus;

    /**
     * @param UserSolution $userSolution
     */
    public function __construct(int $taskId, int $solutionId, SolutionStatus $solutionStatus)
    {
        $this->solutionId = $solutionId;
        $this->taskId = $taskId;
        $this->solutionStatus = $solutionStatus;
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
     * @return SolutionStatus
     */
    public function getSolutionStatus(): SolutionStatus
    {
        return $this->solutionStatus;
    }

    /**
     * @param SolutionStatus $solutionStatus
     */
    public function setSolutionStatus(SolutionStatus $solutionStatus): void
    {
        $this->solutionStatus = $solutionStatus;
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