<?php

namespace App\DTO;

use App\Config\TaskStatus;
use App\Entity\User;
use App\Entity\UserSolution;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class TaskSolutions
{
    /**
     * @var UserSolution[]
     */
    private array $tasks;

    private TaskStatus $last;

    /**
     * @param  UserSolution[] $tasks
     */
    public function __construct(array $tasks)
    {
        foreach ($tasks as $task) {
            $this->tasks[] = new TaskStatusRequest($task->getId(), $task->getTask()->getId(), $task->getStatus());
        }

        if (!empty($this->tasks)) {
            $this->last = $this->tasks[0]->getTaskStatus();
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
     * @return TaskStatus
     */
    public function getLast(): TaskStatus
    {
        return $this->last;
    }

    /**
     * @param TaskStatus $last
     */
    public function setLast(TaskStatus $last): void
    {
        $this->last = $last;
    }


}