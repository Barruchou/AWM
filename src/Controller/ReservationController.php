<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Reservation;
use App\Form\ReservationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    /**
     * @Route("/reserve", name="reserve")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function reserve(EntityManagerInterface $entityManager, Request $request): Response
    {
        $error = null;
        $reservation = new Reservation();
        $form = $this->createForm(ReservationFormType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($reservation->getSeats() > 0){
                $roomSeats = $reservation->getSession()->getRoom()->getSeats();
                $sessions = $entityManager->getRepository(Reservation::class)->findAll();
                $reservedSeats = 0;
                foreach ($sessions as $session){
                    $reservedSeats += $session->getSeats();
                }
                if($roomSeats - $reservedSeats >= $reservation->getSeats()){
                    $reservation->setUser($this->getUser());
                    $reservation->setSeats($reservation->getSeats());
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($reservation);
                    $entityManager->flush();

                    return $this->redirectToRoute('reservationSummary', ['id' => $reservation->getId()]);
                }
                else{
                    $error = "There are only ". ($roomSeats - $reservedSeats)." seats left";
                }
            }
            else{
                $error = "You must pick a number of seats higher than 0";
            }
        }

        return $this->render('reservation/reserve.html.twig', [
            'reservationForm' => $form->createView(),
            'error' => $error
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
        $session = new Session();
        $session->getFlashBag()->add('notice', 'Your reservation has been canceled.');
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
