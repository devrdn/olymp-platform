<?php

namespace App\Entity;

use App\Repository\TaskTestRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskTestRepository::class)]
class TaskTest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'taskTests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?task $task_id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $input_data = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $output_data = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTaskId(): ?task
    {
        return $this->task_id;
    }

    public function setTaskId(?task $task_id): self
    {
        $this->task_id = $task_id;

        return $this;
    }

    public function getInputData(): ?string
    {
        return $this->input_data;
    }

    public function setInputData(?string $input_data): self
    {
        $this->input_data = $input_data;

        return $this;
    }

    public function getOutputData(): ?string
    {
        return $this->output_data;
    }

    public function setOutputData(?string $output_data): self
    {
        $this->output_data = $output_data;

        return $this;
    }
}
