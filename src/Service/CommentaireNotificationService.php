<?php

namespace App\Service;

use App\Entity\Commentaire;
use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class CommentaireNotificationService
{
    private MailerInterface $mailer;
    private string $senderAddress;

    public function __construct(MailerInterface $mailer, string $senderAddress)
    {
        $this->mailer = $mailer;
        $this->senderAddress = $senderAddress;
    }

    public function notifyNewCommentaire(Commentaire $commentaire, string $recipientEmail): void
    {
        $auteur = $commentaire->getAuteur();
        $ticket = $commentaire->getTicket();

        $subject = "Nouveau commentaire sur le ticket #{$ticket->getId()}";

        $content = "Bonjour,\n\n";
        $content .= "Un nouveau commentaire a été ajouté sur le ticket #{$ticket->getId()} ({$ticket->getTitreTicket()}).\n\n";
        $content .= "Auteur: {$auteur->getPrenom()} {$auteur->getNom()}\n";
        $content .= "Date: {$commentaire->getDateCreation()->format('d/m/Y H:i')}\n\n";
        $content .= "Contenu du commentaire :\n";
        $content .= $commentaire->getContenu();

        $email = (new Email())
            ->from($this->senderAddress)
            ->to($recipientEmail)
            ->subject($subject)
            ->text($content);

        $this->mailer->send($email);
    }
}