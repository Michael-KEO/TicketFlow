<?php

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
        // Utiliser la nouvelle méthode pour envoyer des notifications de commentaires
        $this->mailService->sendCommentNotification($commentaire);
    }
}