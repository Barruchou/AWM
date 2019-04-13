<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Reservation;
use App\Entity\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function home(EntityManagerInterface $entityManager)
    {
        $movies = $entityManager->getRepository(Movie::class)->findAll();
        $sessions = $entityManager->getRepository(Session::class)->findAll();

        return $this->render('home/home.html.twig', array(
            'moviesList' => $movies,
            'sessionsList' => $sessions
        ));
    }
}
