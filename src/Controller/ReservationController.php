<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    /**
     * @Route("/reserve", name="app_reserve")
     * @param Request $request
     * @return Response
     */
    public function reserve(Request $request): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationFormType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservation->setUser($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('reservation/reserve.html.twig', [
            'reservationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/reservation", name="app_reservation")
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function getReservations(EntityManagerInterface $entityManager)
    {
        $reservations = $entityManager->getRepository(Reservation::class)->findAll();

        return $this->render('reservation/reservation.html.twig', array(
            'reservationsList' => $reservations
        ));
    }

//    /**
//     * @Route("/deleteReservation/{id}", name="deleteReservation")
//     * @ParamConverter("id", class=Task::class)
//     * @param Reservation $reservation
//     * @param EntityManagerInterface $entityManager
//     * @return \Symfony\Component\HttpFoundation\RedirectResponse
//     */
//    public function deleteReservation(Reservation $reservation, EntityManagerInterface $entityManager)
//    {
//        $entityManager->remove($reservation);
//        $entityManager->flush();
//        return $this->redirectToRoute('app_reservation');
//    }
}
