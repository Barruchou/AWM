<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    /**
     * @Route("/reserve", name="reserve")
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

            return $this->redirectToRoute('reservationSummary', ['id' => $reservation->getId()]);
        }

        return $this->render('reservation/reserve.html.twig', [
            'reservationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/reservation", name="reservation")
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function getReservations(EntityManagerInterface $entityManager)
    {
        $reservations = $entityManager->getRepository(Reservation::class)->findBy(["user" => $this->getUser()]);

        return $this->render('reservation/reservation.html.twig', array(
            'reservationsList' => $reservations
        ));
    }

    /**
     * @Route("/deleteReservation/{id}", name="deleteReservation")
     * @ParamConverter("id", class=Reservation::class)
     * @param Reservation $reservation
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteReservation(Reservation $reservation, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($reservation);
        $entityManager->flush();
        return $this->redirectToRoute('reservation');
    }

    /**
     * @Route("/reservationSummary/{id}", name="reservationSummary")
     * @ParamConverter("id", class=Reservation::class)
     * @param Reservation $reservation
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function getReservationById(Reservation $reservation, EntityManagerInterface $entityManager)
    {
        $reservation = $entityManager->getRepository(Reservation::class)->find($reservation);

        return $this->render('reservation/summary.html.twig', array(
            'reservation' => $reservation
        ));
    }
}
