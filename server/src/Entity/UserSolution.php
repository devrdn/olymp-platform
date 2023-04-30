<?php

namespace App\Entity;

use App\Repository\UserSolutionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserSolutionRepository::class)]
class UserSolution
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Task $task = null;

    #[ORM\Column(length: 255)]
    private ?string $filename = null;

    #[ORM\Column]
    private ?int $status = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $uploadedAt = null;
    
    public function __construct(User $user, Task $task, string $filename, int $status = 0)
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

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
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
