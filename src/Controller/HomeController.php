<?php

namespace App\Controller;

use App\Repository\WorkoutRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(WorkoutRepository $wr, Security $security): Response
    {
        $paginator = new Paginator($wr->getQueryForUser($security->getUser()), $fetchJoinCollection = true);

        return $this->render('home/index.html.twig', [
            'workouts' => $paginator
        ]);
    }
}
