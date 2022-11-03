<?php

namespace App\Controller;

use App\Entity\Workout;
use App\Entity\WorkoutHistory;
use App\Repository\ExerciseRepository;
use App\Repository\WorkoutHistoryRepository;
use App\Repository\WorkoutRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

class WorkoutController extends AbstractController
{
    #[Route('/workout/start', name: 'app_workout_start')]
    public function start(WorkoutRepository $wr, Security $security): Response
    {
        $workout = new Workout();
        $workout->setUser($security->getUser());
        $workout->setCreatedAt(new \DateTimeImmutable());

        $wr->save($workout, true);

        return $this->redirectToRoute('app_workout_active', ['workoutId' => $workout->getId()]);
    }

    #[Route('/workout/active/{workoutId}', name: 'app_workout_active')]
    public function active(int $workoutId, WorkoutRepository $wr, Security $security): Response
    {
        $workout = $wr->findForUser($workoutId, $security->getUser());

        if(false === $workout instanceof Workout) {
            throw $this->createNotFoundException("Workout not found");
        }

        if(null !== $workout->getEndedAt()) {
            throw $this->createNotFoundException("Workout has already ended");
        }

        return $this->render('workout/active.html.twig', [
            'workout' => $workout
        ]);
    }

    #[Route('/workout/finished/{workoutId}', name: 'app_workout_finished')]
    public function finished(int $workoutId, WorkoutRepository $wr, Security $security): Response
    {
        $workout = $wr->findForUser($workoutId, $security->getUser());

        if(false === $workout instanceof Workout) {
            throw $this->createNotFoundException("Workout not found");
        }

        if(null === $workout->getEndedAt()) {
            return $this->redirectToRoute('app_workout_active', ['workoutId' => $workoutId]);
        }

        return $this->render('workout/finished.html.twig', [
            'workout' => $workout
        ]);
    }

    #[Route('/workout/active/{workoutId}/add', name: 'app_workout_active_add', methods: ["POST"])]
    public function add(int $workoutId, WorkoutRepository $wr, WorkoutHistoryRepository $whr, ExerciseRepository $er, Security $security, Request $request): Response
    {
        $workout = $wr->findForUser($workoutId, $security->getUser());

        if(false === $workout instanceof Workout) {
            throw $this->createNotFoundException("Workout not found");
        }

        if(null !== $workout->getEndedAt()) {
            throw $this->createNotFoundException("Workout has already ended");
        }

        $exercise = $er->findOrCreateForUser($request->request->get('exercise'), $security->getUser());
        $workoutHistory = new WorkoutHistory();
        $workoutHistory->setSets($request->request->get('sets'));
        $workoutHistory->setReps($request->request->get('reps'));
        $workoutHistory->setWeight($request->request->get('weight'));
        $workoutHistory->setDuration((float)$request->request->get('duration'));
        $workoutHistory->setHistoryOrder($workout->getWorkoutHistories()->count());
        $workoutHistory->setExercise($exercise);
        $workout->addWorkoutHistory($workoutHistory);
        $whr->save($workoutHistory, true);
        $wr->save($workout, true);

        return $this->redirectToRoute('app_workout_active', ['workoutId' => $workout->getId()]);
    }

    #[Route('/workout/stop/{workoutId}', name: 'app_workout_stop', methods: ['POST'])]
    public function stop(int $workoutId, WorkoutRepository $wr, Security $security): Response
    {
        $workout = $wr->findForUser($workoutId, $security->getUser());

        if(false === $workout instanceof Workout) {
            throw $this->createNotFoundException("Workout not found");
        }

        if(null !== $workout->getEndedAt()) {
            throw $this->createNotFoundException("Workout has already ended");
        }

        $workout->setEndedAt(new \DateTime());
        $wr->save($workout, true);

        return $this->redirectToRoute('app_home');
    }

    #[Route('/workout/delete/{workoutId}', name: 'app_workout_delete', methods: ['POST'])]
    public function delete(int $workoutId, WorkoutRepository $wr, Security $security): Response
    {
        $workout = $wr->findForUser($workoutId, $security->getUser());

        if(false === $workout instanceof Workout) {
            throw $this->createNotFoundException("Workout not found");
        }

        if(null === $workout->getEndedAt()) {
            throw $this->createNotFoundException("Workout not not yet ended");
        }

        $wr->remove($workout, true);

        return $this->redirectToRoute('app_home');
    }
}
