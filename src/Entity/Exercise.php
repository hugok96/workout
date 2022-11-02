<?php

namespace App\Entity;

use App\Repository\ExerciseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExerciseRepository::class)]
class Exercise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'exercises')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'exercise', targetEntity: WorkoutHistory::class, orphanRemoval: true)]
    private Collection $workoutHistories;

    public function __construct()
    {
        $this->workoutHistories = new ArrayCollection();
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, WorkoutHistory>
     */
    public function getWorkoutHistories(): Collection
    {
        return $this->workoutHistories;
    }

    public function addWorkoutHistory(WorkoutHistory $workoutHistory): self
    {
        if (!$this->workoutHistories->contains($workoutHistory)) {
            $this->workoutHistories->add($workoutHistory);
            $workoutHistory->setExercise($this);
        }

        return $this;
    }

    public function removeWorkoutHistory(WorkoutHistory $workoutHistory): self
    {
        if ($this->workoutHistories->removeElement($workoutHistory)) {
            // set the owning side to null (unless already changed)
            if ($workoutHistory->getExercise() === $this) {
                $workoutHistory->setExercise(null);
            }
        }

        return $this;
    }
}
