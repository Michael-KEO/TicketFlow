<?php

namespace App\Controller;



use App\Entity\Ticket;
use App\Repository\TicketRepository;
use App\Service\MailNotificationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class NotificationTestController extends AbstractController
{
    #[Route('/test-notification/{id}', name: 'test_notification')]
    public function testNotification(int $id, TicketRepository $ticketRepository, MailNotificationService $mailer): Response
    {
        $ticket = $ticketRepository->find($id);

        if (!$ticket) {
            throw $this->createNotFoundException('Ticket introuvable.');
        }

        // Simuler le changement de statut
        $ticket->setStatutTicket('Résolu');
        $ticket->setDateResolution(new \DateTime());
        $this->getDoctrine()->getManager()->flush();

        // Envoi du mail
        $mailer->sendStatusUpdate($ticket);

        return new Response('E-mail envoyé pour le ticket ID : ' . $ticket->getId());
    }
}

