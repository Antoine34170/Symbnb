<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Comment;
use App\Form\AdminCommentType;
use App\Repository\CommentRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{
    /**
     * Permet d'afficher la liste des commentaires en mode_admin
     *
     * @Route("/admin/comment/{page<\d+>?1}", name="admin_comment_index")
     * 
     * @param CommentRepository $repo
     * 
     */
    public function index(CommentRepository $repo, $page, PaginationService $pagination): Response
    {
        $pagination->setEntityClass(Comment::class)
        ->setPage($page);
                
        return $this->render('admin/comment/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'édition des commentaires en mode admin
     * 
     * @Route("/admin/comment/{id}/edit", name="admin_comment_edit")
     * 
     * @param Ad $ad
     * @param Request $request
     * @param EntityManagerInterface $manager
     */
    public function edit(Comment $comment, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AdminCommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le commentaire <strong>   {$comment->getId()} </strong> a bien été modifiée"
            );

            return $this->redirectToRoute('admin_comment_index');
        }
        return $this->render('admin/comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer uncommentaire
     * 
     * @Route("/admin/comment/{id}/delete", name="admin_comment_delete")
     * 
     * @param Comment $comment
     * @param EntityManagerInterface $manager
     * 
     * @return Reponse
     */
    public function delete(Comment $comment, EntityManagerInterface $manager)
    {
        $manager->remove($comment);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le commentaire <strong>   {$comment->getId()} </strong> a bien été supprimée"
        );
        return $this->redirectToRoute('admin_comment_index');
    }
}
