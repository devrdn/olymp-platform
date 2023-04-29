<?php

namespace App\Entity;

use App\Repository\TaskTestRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskTestRepository::class)]
#[ORM\UniqueConstraint(name: 'unique_tests', columns: ['task_id', 'input_data'])]
class TaskTest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'taskTests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?task $task = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $input_data = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $output_data = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTask(): ?task
    {
        return $this->task;
    }

    public function setTask(?task $task): self
    {
        $this->task = $task;

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
