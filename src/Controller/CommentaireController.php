<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Form\CommentaireTypeForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CommentaireController extends AbstractController
{
    #[Route('/commentaire/{id}/edit', name: 'app_commentaire_edit')]
    public function edit(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        // Vérifier que l'utilisateur est l'auteur du commentaire
        if ($this->getUser() !== $commentaire->getAuteur() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas modifier ce commentaire.');
        }

        $form = $this->createForm(CommentaireTypeForm::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Mettre à jour la date de modification
            $commentaire->setDateModification(new \DateTime());
            $entityManager->flush();

            $this->addFlash('success', 'Commentaire mis à jour avec succès');

            // Rediriger vers la page du ticket
            return $this->redirectToRoute('ticket_show', ['id' => $commentaire->getTicket()->getId()]);
        }

        return $this->render('commentaire/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    #[Route('/commentaire/{id}/moderer', name: 'app_commentaire_moderer')]
    #[IsGranted('ROLE_ADMIN')]
    public function moderer(Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        // Basculer l'état de modération
        $commentaire->setModere(!$commentaire->isModere());
        $entityManager->flush();

        $status = $commentaire->isModere() ? 'modéré' : 'non modéré';
        $this->addFlash('success', "Le commentaire est maintenant $status");

        // Rediriger vers la page du ticket
        return $this->redirectToRoute('ticket_show', ['id' => $commentaire->getTicket()->getId()]);
    }

    #[Route('/commentaire/{id}/delete', name: 'app_commentaire_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commentaire->getId(), $request->request->get('_token'))) {
            $ticketId = $commentaire->getTicket()->getId();
            $entityManager->remove($commentaire);
            $entityManager->flush();

            $this->addFlash('success', 'Commentaire supprimé avec succès');
            return $this->redirectToRoute('ticket_show', ['id' => $ticketId]);
        }

        return $this->redirectToRoute('ticket_show', ['id' => $commentaire->getTicket()->getId()]);
    }
}