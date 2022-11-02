<?php

namespace App\Entity;

use App\Repository\WorkoutHistoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WorkoutHistoryRepository::class)]
class WorkoutHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'workoutHistories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Workout $workout = null;

    #[ORM\ManyToOne(inversedBy: 'workoutHistories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Exercise $exercise = null;

    #[ORM\Column]
    private ?int $history_order = null;

    #[ORM\Column]
    private ?int $sets = null;

    #[ORM\Column]
    private ?int $reps = null;

    #[ORM\Column(nullable: true)]
    private ?float $weight = null;

    #[ORM\Column(nullable: true)]
    private ?float $duration = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWorkout(): ?Workout
    {
        return $this->workout;
    }

    public function setWorkout(?Workout $workout): self
    {
        $this->workout = $workout;

        return $this;
    }

    public function getExercise(): ?Exercise
    {
        return $this->exercise;
    }

    public function setExercise(?Exercise $exercise): self
    {
        $this->exercise = $exercise;

        return $this;
    }

    public function getHistoryOrder(): ?int
    {
        return $this->history_order;
    }

    public function setHistoryOrder(int $history_order): self
    {
        $this->history_order = $history_order;

        return $this;
    }

    public function getSets(): ?int
    {
        return $this->sets;
    }

    public function setSets(int $sets): self
    {
        $sets = $sets == 0 ? 1 : $sets;
        $this->sets = $sets;

        return $this;
    }

    public function getReps(): ?int
    {
        return $this->reps;
    }

    public function setReps(int $reps): self
    {
        $reps = $reps == 0 ? 1 : $reps;
        $this->reps = $reps;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(?float $weight): self
    {
        $weight = $weight == 0 ? null : $weight;
        $this->weight = $weight;

        return $this;
    }

    public function getDuration(): ?float
    {
        return $this->duration;
    }

    public function setDuration(?float $duration): self
    {
        $duration = $duration == 0 ? null : $duration;
        $this->duration = $duration;

        return $this;
    }
}
