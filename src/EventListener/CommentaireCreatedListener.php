<?php
// src/EventListener/CommentaireCreatedListener.php

namespace App\EventListener;

use App\Event\CommentaireCreatedEvent;
use App\Service\MailNotificationService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CommentaireCreatedListener implements EventSubscriberInterface
{
    private MailNotificationService $mailService;

    public function __construct(MailNotificationService $mailService)
    {
        $this->mailService = $mailService;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CommentaireCreatedEvent::class => 'onCommentaireCreated',
        ];
    }

    public function onCommentaireCreated(CommentaireCreatedEvent $event): void
    {
        $commentaire = $event->getCommentaire();
        $ticket = $commentaire->getTicket();
        $auteur = $commentaire->getAuteur();

        // Envoyer un email au créateur du ticket
        $destinataire = $ticket->getRapporteur()->getEmail();
        $sujet = "Nouveau commentaire sur le ticket #{$ticket->getTicketId()}";

        $contenu = "Bonjour,\n\n";
        $contenu .= "Un nouveau commentaire a été ajouté sur le ticket #{$ticket->getTicketId()} : {$ticket->getTitreTicket()}.\n\n";
        $contenu .= "Auteur du commentaire : {$auteur->getPrenom()} {$auteur->getNom()}\n";
        $contenu .= "Contenu du commentaire :\n{$commentaire->getContenu()}\n\n";
        $contenu .= "Vous pouvez consulter le ticket à l'adresse suivante : [lien vers le ticket]";

        $this->mailService->sendEmail($destinataire, $sujet, $contenu);

        // Si vous souhaitez envoyer également aux développeurs assignés
        if ($ticket->getDeveloppeur()) {
            $developpeurEmail = $ticket->getDeveloppeur()->getEmail();
            $this->mailService->sendEmail($developpeurEmail, $sujet, $contenu);
        }
    }
}