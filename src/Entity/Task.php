<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ApiResource]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column]
    private ?int $priority = null;

    // Relation ManyToOne vers Team
    #[ORM\ManyToOne(targetEntity: Team::class, inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Team $team = null;

    // Relation ManyToMany pour les dépendances entre tâches
    #[ORM\ManyToMany(targetEntity: Task::class)]
    #[ORM\JoinTable(name: 'task_dependencies')]
    private Collection $dependencies;

    public function __construct()
    {
        $this->dependencies = new ArrayCollection();
    }

    // Getters et setters...
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): static
    {
        $this->priority = $priority;

        return $this;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): static
    {
        $this->team = $team;

        return $this;
    }

    // Gestion des dépendances
    public function getDependencies(): Collection
    {
        return $this->dependencies;
    }

    public function addDependency(Task $task): static
    {
        if (!$this->dependencies->contains($task)) {
            $this->dependencies[] = $task;
        }

        return $this;
    }

    public function removeDependency(Task $task): static
    {
        $this->dependencies->removeElement($task);

        return $this;
    }
}


