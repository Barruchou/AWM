<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Reservation;
use App\Entity\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    /**
     * @Route("/movie/{id}", name="movie")
     * @ParamConverter("id", class=Movie::class)
     * @param Movie $movie
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function getMoviePage(Movie $movie, EntityManagerInterface $entityManager)
    {
        $movie = $entityManager->getRepository(Movie::class)->findOneBy(["id" => $movie]);
        $sessions = $entityManager->getRepository(Session::class)->findBy(["movie" => $movie->getId()]);

        return $this->render('movie/movie.html.twig', array(
            'movie' => $movie,
            'sessions' => $sessions
        ));
    }
}
