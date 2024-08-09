<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    // #[ORM\Column(type: Types::DATE_MUTABLE)]
    // private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(nullable: true)]
    private ?bool $archive = null;

    /**
     * @var Collection<int, Employee>
     */
    #[ORM\ManyToMany(targetEntity: Employee::class, inversedBy: 'projects')]
    private Collection $pjAccess;

    public function __construct()
    {
        $this->pjAccess = new ArrayCollection();
    }

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

    // public function getStartDate(): ?\DateTimeInterface
    // {
    //     return $this->startDate;
    // }

    // public function setStartDate(\DateTimeInterface $startDate): static
    // {
    //     $this->startDate = $startDate;

    //     return $this;
    // }

    public function isArchive(): ?bool
    {
        return $this->archive;
    }

    public function setArchive(?bool $archive): static
    {
        $this->archive = $archive;

        return $this;
    }

    /**
     * @return Collection<int, Employee>
     */
    public function getPjAccess(): Collection
    {
        return $this->pjAccess;
    }

    public function addPjAccess(Employee $pjAccess): static
    {
        if (!$this->pjAccess->contains($pjAccess)) {
            $this->pjAccess->add($pjAccess);
        }

        return $this;
    }

    public function removePjAccess(Employee $pjAccess): static
    {
        $this->pjAccess->removeElement($pjAccess);

        return $this;
    }
}
