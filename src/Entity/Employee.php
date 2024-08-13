<?php

namespace App\Entity;

use App\Enum\ContractList;
use App\Repository\EmployeeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
class Employee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank()]
    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[Assert\NotBlank()]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Assert\NotBlank()]
    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    private ?string $fullName = null;

    #[Assert\NotBlank()]
    #[ORM\Column(length: 24)]
    private ?ContractList $contract = null;

    #[Assert\NotBlank()]
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $entryDate = null;

    /**
     * @var Collection<int, Task>
     */
    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'member')]
    private Collection $tasks;

    /**
     * @var Collection<int, Project>
     */
    #[ORM\ManyToMany(targetEntity: Project::class, mappedBy: 'pjAccess')]
    private Collection $projects;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
        $this->projects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    // public function getPassword(): ?string
    // {
    //     return $this->password;
    // }

    // public function setPassword(string $password): static
    // {
    //     $this->password = $password;

    //     return $this;
    // }

    // public function getRole(): ?string
    // {
    //     return $this->role;
    // }

    // public function setRole(string $role): static
    // {
    //     $this->role = $role;

    //     return $this;
    // }

    public function getContract(): ?ContractList
    {
        return $this->contract;
    }

    public function setContract(ContractList $contract): static
    {
        $this->contract = $contract;

        return $this;
    }

    // public function isActive(): ?bool
    // {
    //     return $this->active;
    // }

    // public function setActive(?bool $active): static
    // {
    //     $this->active = $active;

    //     return $this;
    // }

    public function getEntryDate(): ?\DateTimeInterface
    {
        return $this->entryDate;
    }

    public function setEntryDate(?\DateTimeInterface $entryDate): static
    {
        $this->entryDate = $entryDate;

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): static
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setMember($this);
        }

        return $this;
    }

    public function removeTask(Task $task): static
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getMember() === $this) {
                $task->setMember(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->addPjAccess($this);
        }

        return $this;
    }

    public function removeProject(Project $project): static
    {
        if ($this->projects->removeElement($project)) {
            $project->removePjAccess($this);
        }

        return $this;
    }

    public function getfullName(): ?string
    {
        return $this->name . " " . $this->firstname;
    }

    public function setfullName(string $name, $firstname): static
    {
        $this->fullName = $name . " " . $firstname;

        return $this;
    }
}
