<?php

namespace App\Entity;

use App\Repository\WorkoutRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Security;

#[ORM\Entity(repositoryClass: WorkoutRepository::class)]
class Workout
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'workouts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $ended_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deleted_at = null;

    #[ORM\OneToMany(mappedBy: 'workout', targetEntity: WorkoutHistory::class, orphanRemoval: true)]
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getEndedAt(): ?\DateTimeInterface
    {
        return $this->ended_at;
    }

    public function setEndedAt(?\DateTimeInterface $ended_at): self
    {
        $this->ended_at = $ended_at;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deleted_at;
    }

    public function setDeletedAt(?\DateTimeImmutable $deleted_at): self
    {
        $this->deleted_at = $deleted_at;

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
            $workoutHistory->setWorkout($this);
        }

        return $this;
    }

    public function removeWorkoutHistory(WorkoutHistory $workoutHistory): self
    {
        if ($this->workoutHistories->removeElement($workoutHistory)) {
            // set the owning side to null (unless already changed)
            if ($workoutHistory->getWorkout() === $this) {
                $workoutHistory->setWorkout(null);
            }
        }

        return $this;
    }
}
