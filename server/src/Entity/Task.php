<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $time_limit = null;

    #[ORM\Column]
    private ?int $memory_limit = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $example_input = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $example_output = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $restriction = null;

    #[ORM\OneToMany(mappedBy: 'task', targetEntity: TaskTest::class, orphanRemoval: true)]
    private Collection $taskTests;

    #[ORM\OneToOne(mappedBy: 'task', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private ?TaskMeta $taskMeta = null;

    #[ORM\Column]
    private ?bool $published = null;


    public function __construct()
    {
        $this->taskTests = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTimeLimit(): ?int
    {
        return $this->time_limit;
    }

    public function setTimeLimit(int $time_limit): self
    {
        $this->time_limit = $time_limit;

        return $this;
    }

    public function getMemoryLimit(): ?int
    {
        return $this->memory_limit;
    }

    public function setMemoryLimit(int $memory_limit): self
    {
        $this->memory_limit = $memory_limit;

        return $this;
    }

    public function getExampleInput(): ?string
    {
        return $this->example_input;
    }

    public function setExampleInput(?string $example_input): self
    {
        $this->example_input = $example_input;

        return $this;
    }

    public function getExampleOutput(): ?string
    {
        return $this->example_output;
    }

    public function setExampleOutput(?string $example_output): self
    {
        $this->example_output = $example_output;

        return $this;
    }

    public function getRestriction(): ?string
    {
        return $this->restriction;
    }

    public function setRestriction(?string $restriction): self
    {
        $this->restriction = $restriction;

        return $this;
    }

    /**
     * @return Collection<int, TaskTest>
     */
    public function getTaskTests(): Collection
    {
        return $this->taskTests;
    }

    public function addTaskTest(TaskTest $taskTest): self
    {
        if (!$this->taskTests->contains($taskTest)) {
            $this->taskTests->add($taskTest);
            $taskTest->setTask($this);
        }

        return $this;
    }

    public function removeTaskTest(TaskTest $taskTest): self
    {
        if ($this->taskTests->removeElement($taskTest)) {
            // set the owning side to null (unless already changed)
            if ($taskTest->getTask() === $this) {
                $taskTest->setTask(null);
            }
        }

        return $this;
    }

    public function getTaskMeta(): ?TaskMeta
    {
        return $this->taskMeta;
    }

    public function setTaskMeta(TaskMeta $taskMeta): self
    {
        // set the owning side of the relation if necessary
        if ($taskMeta->getTask() !== $this) {
            $taskMeta->setTask($this);
        }

        $this->taskMeta = $taskMeta;

        return $this;
    }

    public function isPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): self
    {
        $this->published = $published;

        return $this;
    }
}
