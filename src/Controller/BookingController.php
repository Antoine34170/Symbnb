<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Form\BookingType;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookingController extends AbstractController
{
    /**
     * Permet faire une réservation
     * 
     * @Route("/ads/{slug}/book", name="booking_create")
     * 
     * @IsGranted("ROLE_USER")
     */
    public function book(Ad $ad, Request $request, EntityManagerInterface $manager): Response
    {
        $booking = new Booking();

        $form = $this->createForm(BookingType::class, $booking);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $booking->setBooker($this->getUser())
                ->setAd($ad);

            // Si les dates ne sont pa disponibles , message d'erreur
            if (!$booking->isBookableDates()) {
                $this->addFlash(
                    'warning',
                    'Les dates que vous ahez choisies ne peuvent être réservées : elles sont déjà prises'
                );
            } else {
                // Sinon en enregistrement
                $manager->persist($booking);
                $manager->flush();

                // return $this->redirectToRoute('ads_index');
                return $this->redirectToRoute('booking_show', [
                    'id' => $booking->getId(),
                    'withAlert' => true
                ]);
            }
        }
        return $this->render('booking/book.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }


    /**
     * Permet d'afficher une réservation
     * 
     * @Route("/booking/{id}", name="booking_show")
     * @Security("is_granted('ROLE_USER') and user === booking.getBooker()", message="Cette réservation ne vous appartient pas, vous ne pouvez pas la modifier")
     *  
     * @return Response
     */

    public function show(Booking $booking, Request $request, EntityManagerInterface $manager)
    {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $comment->setCreatedAt(new \DateTime())
                ->setAd($booking->getAd())
                ->setAuthor($this->getUser());

            $this->addFlash(
                'success',
                'Votre avis a bien été pris en compte, nous vous en remercions infiniment'
            );

            $manager->persist($comment);
            $manager->flush();
        }

        return $this->render('booking/show.html.twig', [
            'booking' => $booking,
            'form' => $form->createView()
        ]);
    }
}
