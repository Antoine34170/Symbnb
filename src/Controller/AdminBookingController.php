<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Form\AdminCommentType;
use App\Repository\BookingRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminBookingController extends AbstractController
{
    /**
     * Permet d'afficher la liste des réservations en mode_admin
     *
     * @Route("/admin/booking/{page<\d+>?1}", name="admin_booking_index")
     * 
     * @param BookingRepository $repo
     * 
     */
    public function index(BookingRepository $repo, $page, PaginationService $pagination): Response
    {
        $pagination->setEntityClass(Booking::class)
            ->setPage($page);

        return $this->render('admin/booking/index.html.twig', [
            'pagination' => $pagination

        ]);
    }

    /**
     * Permet d'afficher le formulaire d'édition des réservations en mode admin
     * 
     * @Route("/admin/booking/{id}/edit", name="admin_booking_edit")
     * 
     * @param Ad $ad
     * @param Request $request
     * @param EntityManagerInterface $manager
     */
    public function edit(Booking $booking, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AdminBookingType::class, $booking);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($booking);
            $manager->flush();

            $this->addFlash(
                'success',
                "La réservations <strong>   {$booking->getId()} </strong> a bien été modifiée"
            );

            return $this->redirectToRoute('admin_booking_index');
        }
        return $this->render('admin/booking/edit.html.twig', [
            'booking' => $booking,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer uncommentaire
     * 
     * @Route("/admin/booking/{id}/delete", name="admin_booking_delete")
     * 
     * @param booking $booking
     * @param EntityManagerInterface $manager
     * 
     * @return Reponse
     */
    public function delete(booking $booking, EntityManagerInterface $manager)
    {
        $manager->remove($booking);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le commentaire <strong>   {$booking->getId()} </strong> a bien été supprimée"
        );
        return $this->redirectToRoute('admin_booking_index');
    }
}
