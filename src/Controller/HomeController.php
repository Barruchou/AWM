<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Reservation;
use App\Entity\Session;
use App\Form\ReservationFormType;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("", name="home")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param MovieRepository $movieRepository
     * @return Response
     */
    public function home(EntityManagerInterface $entityManager, Request $request, MovieRepository $movieRepository)
    {
        $searchTitle = $request->query->get('title');
        $movies = $entityManager->getRepository(Movie::class)->findAll();
        $sessions = $entityManager->getRepository(Session::class)->findAll();

        if($searchTitle !== null){
            $movies = $movieRepository->findByTitle($searchTitle);
        }

        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('home', ['title' => $form->getData()]);
        }

        return $this->render('home/home.html.twig', array(
            'moviesList' => $movies,
            'sessionsList' => $sessions,
            'searchForm' => $form->createView(),
        ));
    }

//    /**
//     * @Route("/search", name="search")
//     * @param EntityManagerInterface $entityManager
//     * @param Request $request
//     * @param MovieRepository $movieRepository
//     * @return Response
//     */
//    public function search(EntityManagerInterface $entityManager, Request $request, MovieRepository $movieRepository)
//    {
//        $searchTitle = $request->query->get('title');
//        $movies = $movieRepository->findByTitle($searchTitle);
////        dd($movies);
//        $sessions = $entityManager->getRepository(Session::class)->findAll();
//
//        $form = $this->createForm(SearchType::class);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            return $this->redirectToRoute('search', ['title' => $form->getData()]);
//        }
//
//        return $this->render('home/home.html.twig', array(
//            'moviesList' => $movies,
//            'sessionsList' => $sessions,
//            'searchForm' => $form->createView(),
//        ));
//    }
}
