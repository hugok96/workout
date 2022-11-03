<?php

namespace App\Controller;

use App\Entity\Workout;
use App\Repository\WorkoutRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(WorkoutRepository $wr, Security $security, PaginatorInterface $paginator, Request $request): Response
    {
        $workoutPaginator = $paginator->paginate(
            $wr->getQueryForUser($security->getUser()),
            $request->query->getInt('page', 1),
            10
        );

        $time = $wr->getTimeSpentForUser($security->getUser()) ?? 0;
        $days = floor($time/86400);
        $hours = ($time/3600) % 24;
        $minutes = $time % 60;
        $totalTime = "{$days} days, {$hours}h{$minutes}m";
        return $this->render('home/index.html.twig', [
            'paginator' => $workoutPaginator,
            'totalTime' => $totalTime,
        ]);
    }
}
