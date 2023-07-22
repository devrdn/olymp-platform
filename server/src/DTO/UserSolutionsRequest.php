<?php

namespace App\DTO;


use App\Config\SolutionStatus;
use App\Entity\User;
use App\Entity\UserSolution;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class UserSolutionsRequest
{
    /**
     * @var UserSolution[]
     */
    private array $tasks;

    private SolutionStatus $last;

    /**
     * @param  UserSolution[] $tasks
     */
    public function __construct(array $tasks)
    {
        foreach ($tasks as $task) {
            $this->tasks[] = new UserSolutionsStatusRequest($task->getId(), $task->getTask()->getId(), SolutionStatus::tryFrom($task->getStatus()));
        }

        if (!empty($this->tasks)) {
            $this->last = $this->tasks[0]->getSolutionStatus();
        }
    }


    /**
     * @return  UserSolution[]
     */
    public function getTasks(): array
    {
        return $this->tasks;
    }

    /**
     * @param UserSolution[] $tasks
     */
    public function setTasks(array $tasks): void
    {
        $this->tasks = $tasks;
    }

    /**
     * @return SolutionStatus
     */
    public function getLast(): SolutionStatus
    {
        return $this->last;
    }

    /**
     * @param SolutionStatus $last
     */
    public function setLast(SolutionStatus $last): void
    {
        $this->last = $last;
    }


}