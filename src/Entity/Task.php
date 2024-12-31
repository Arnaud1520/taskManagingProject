<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\TaskRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ApiResource]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['task:read'])]
    private ?string $name = null;
    
    #[ORM\Column(length: 255)]
    #[Groups(['task:read'])]
    private ?string $description = null;

    // Relation ManyToOne avec Team
    #[ORM\ManyToOne(targetEntity: Team::class, inversedBy: 'tasks')]
    #[ORM\JoinColumn(name: 'team_id', referencedColumnName: 'id', nullable: false)] // Spécification explicite de la colonne
    private ?Team $team = null;

    #[ORM\Column]
    #[Groups(['task:read'])]
    #[Assert\Range(min: 1, max: 5, notInRangeMessage: 'Priority must be between {{ min }} and {{ max }}')]
    private ?int $priority = 1;
    
    #[ORM\Column(length: 255)]
    #[Groups(['task:read'])]
    private ?string $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
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

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): static
    {
        $this->team = $team;
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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;
        return $this;
    }
}
