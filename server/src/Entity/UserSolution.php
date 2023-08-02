<?php

namespace App\Entity;

use App\Config\SolutionStatus;
use App\Repository\UserSolutionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserSolutionRepository::class)]
class UserSolution
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("user_solution")]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Task $task = null;

    #[ORM\Column(length: 255)]
    #[Groups("user_solution")]
    private ?string $filename = null;

    #[ORM\Column(type: "integer", enumType: SolutionStatus::class)]
    #[Groups("user_solution")]
    private SolutionStatus $status;

    #[ORM\Column]
    #[Groups("user_solution")]
    private ?\DateTimeImmutable $uploadedAt = null;
    
    public function __construct(User $user, Task $task, string $filename, SolutionStatus $status = SolutionStatus::QUEUE)
    {
        $this->user = $user;
        $this->task = $task;
        $this->filename = $filename;
        $this->status = $status;
        $this->uploadedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTask(?Task $task): self
    {
        $this->task = $task;

        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status->name;
    }

    public function setStatus(SolutionStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getUploadedAt(): ?\DateTimeImmutable
    {
        return $this->uploadedAt;
    }

    public function setUploadedAt(\DateTimeImmutable $uploadedAt): self
    {
        $this->uploadedAt = $uploadedAt;

        return $this;
    }
}
