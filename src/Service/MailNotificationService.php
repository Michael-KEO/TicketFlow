<?php

namespace App\Service;

use App\Entity\Ticket;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;

class MailNotificationService
{
    private Mailer $mailer;

    public function __construct()
    {
        // Remplace ici par ton vrai mot de passe d'application
        $dsn = 'smtp://ticketflow.notifications@gmail.com:tdsbvcbkcxwtuaua@smtp.gmail.com:587';
        $transport = Transport::fromDsn($dsn);
        $this->mailer = new Mailer($transport);
    }
    public function sendStatusUpdate(Ticket $ticket): void
    {
        $to = $ticket->getDeveloppeur()->getEmail();
        error_log("🔔 Envoi email à : " . $to);
        $html = "
            <p>Bonjour {$ticket->getDeveloppeur()->getPrenom()} {$ticket->getDeveloppeur()->getNom()}</p>
            <p>Le ticket <strong>{$ticket->getTitreTicket()}</strong> a été mis à jour.</p>
            <p><strong>Statut :</strong> {$ticket->getStatutTicket()}</p>
            <p><strong>Projet :</strong> {$ticket->getProjet()->getNomProjet()}</p>
            <p>Bonne journée,<br>L’équipe TicketFlow</p>
        ";

        $email = (new TemplatedEmail())
            ->from('ticketflow.notifications@gmail.com')
            ->to($to)
            ->subject('Mise à jour du ticket #' . $ticket->getId())
            ->html($html)
            ->context([
                'ticket' => $ticket,
            ]);

        $this->mailer->send($email);
    }
}
