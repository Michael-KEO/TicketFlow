<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Entity\Commentaire;
use App\Form\TicketType;
use App\Form\CommentaireType;
use App\Repository\TicketRepository;
use App\Repository\CommentaireRepository;
use App\Service\MailNotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/ticket')]
class TicketController extends AbstractController
{
    #[Route('/', name: 'ticket_index', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index(Request $request, TicketRepository $ticketRepository): Response
    {
        $user = $this->getUser();
        $activeRole = $request->getSession()->get('active_role');
        
        // Filtrer les tickets selon le rôle
        if ($activeRole === 'ROLE_RAPPORTEUR') {
            // Le rapporteur voit ses tickets créés
            $tickets = $ticketRepository->findBy(['rapporteur' => $user], ['dateCreation' => 'DESC']);
        } elseif ($activeRole === 'ROLE_DEV') {
            // Le développeur voit ses tickets assignés
            $tickets = $ticketRepository->findBy(['developpeur' => $user], ['dateCreation' => 'DESC']);
        } else {
            // L'admin voit tous les tickets
            $tickets = $ticketRepository->findBy([], ['dateCreation' => 'DESC']);
        }

        return $this->render('ticket/index.html.twig', [
            'tickets' => $tickets,
            'activeRole' => $activeRole
        ]);
    }

    #[Route('/new', name: 'ticket_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_RAPPORTEUR')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ticket = new Ticket();
        $activeRole = $request->getSession()->get('active_role');
        
        $form = $this->createForm(TicketType::class, $ticket, [
            'is_edit' => false,
            'user_role' => $activeRole
        ]);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Générer un ticket_id unique
            $lastTicket = $entityManager->getRepository(Ticket::class)
                ->findOneBy([], ['ticket_id' => 'DESC']);
            $newTicketId = $lastTicket ? $lastTicket->getTicketId() + 1 : 1;
            
            $ticket->setTicketId($newTicketId);
            $ticket->setRapporteur($this->getUser());
            $ticket->setDateCreation(new \DateTime());
            $ticket->setStatutTicket('Nouveau');

            $entityManager->persist($ticket);
            $entityManager->flush();

            $this->addFlash('success', 'Le ticket #' . $newTicketId . ' a été créé avec succès.');

            return $this->redirectToRoute('ticket_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ticket/new.html.twig', [
            'ticket' => $ticket,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'ticket_show', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function show(
        Request $request, 
        Ticket $ticket, 
        CommentaireRepository $commentaireRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser();
        $activeRole = $request->getSession()->get('active_role');
        
        // Vérifier les permissions
        if ($activeRole === 'ROLE_RAPPORTEUR' && $ticket->getRapporteur() !== $user) {
            throw $this->createAccessDeniedException('Vous ne pouvez voir que vos propres tickets.');
        }
        
        if ($activeRole === 'ROLE_DEV' && $ticket->getDeveloppeur() !== $user) {
            throw $this->createAccessDeniedException('Vous ne pouvez voir que les tickets qui vous sont assignés.');
        }

        // Récupérer les commentaires
        $commentaires = $commentaireRepository->findByTicketOrderedByDate($ticket->getId());

        // Formulaire pour ajouter un commentaire
        $commentaire = new Commentaire();
        $commentForm = $this->createForm(CommentaireType::class, $commentaire);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $commentaire->setTicket($ticket);
            $commentaire->setAuteur($user);
            
            $entityManager->persist($commentaire);
            $entityManager->flush();

            $this->addFlash('success', 'Commentaire ajouté avec succès.');
            
            return $this->redirectToRoute('ticket_show', ['id' => $ticket->getId()]);
        }

        return $this->render('ticket/show.html.twig', [
            'ticket' => $ticket,
            'commentaires' => $commentaires,
            'commentForm' => $commentForm,
            'activeRole' => $activeRole
        ]);
    }
    #[Route('/{id}/edit', name: 'ticket_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function edit(
        Request $request,
        Ticket $ticket,
        EntityManagerInterface $entityManager,
        MailNotificationService $mailService // ← ajout ici
    ): Response
    {
        $user = $this->getUser();
        $activeRole = $request->getSession()->get('active_role');

        // Vérifications des permissions
        if ($activeRole === 'ROLE_RAPPORTEUR' && $ticket->getRapporteur() !== $user) {
            throw $this->createAccessDeniedException('Vous ne pouvez modifier que vos propres tickets.');
        }
        if ($activeRole === 'ROLE_DEV' && $ticket->getDeveloppeur() !== $user && $activeRole !== 'ROLE_ADMIN') {
            throw $this->createAccessDeniedException('Vous ne pouvez modifier que les tickets qui vous sont assignés.');
        }

        $form = $this->createForm(TicketType::class, $ticket, [
            'is_edit' => true,
            'user_role' => $activeRole
        ]);

       
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (in_array($ticket->getStatutTicket(), ['Résolu', 'Fermé', 'Rejeté', 'En cours', 'Ouvert']) && !$ticket->getDateResolution()) {
                $ticket->setDateResolution(new \DateTime());
            }

            $entityManager->flush();

            // 💌 Envoie le mail APRÈS flush
            $mailService->sendStatusUpdate($ticket);

            $this->addFlash('success', 'Le ticket #' . $ticket->getTicketId() . ' a été mis à jour.');
            return $this->redirectToRoute('ticket_show', ['id' => $ticket->getId()]);
        }


        return $this->render('ticket/edit.html.twig', [
            'ticket' => $ticket,
            'form' => $form,
        ]);
        }
    


    #[Route('/{id}/delete', name: 'ticket_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Ticket $ticket, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ticket->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($ticket);
            $entityManager->flush();
            
            $this->addFlash('success', 'Le ticket #' . $ticket->getTicketId() . ' a été supprimé.');
        }

        return $this->redirectToRoute('ticket_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/assign', name: 'ticket_assign', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function assign(Request $request, Ticket $ticket, EntityManagerInterface $entityManager): Response
    {
        $developerId = $request->request->get('developer_id');
        
        if ($developerId) {
            $developer = $entityManager->getRepository(User::class)->find($developerId);
            if ($developer && in_array('ROLE_DEV', $developer->getRoles())) {
                $ticket->setDeveloppeur($developer);
                $ticket->setStatutTicket('Assigné');
                
                $entityManager->flush();
                
                $this->addFlash('success', 'Ticket assigné à ' . $developer->getPrenom() . ' ' . $developer->getNom());
            }
        }

        return $this->redirectToRoute('ticket_show', ['id' => $ticket->getId()]);
    }
   
}