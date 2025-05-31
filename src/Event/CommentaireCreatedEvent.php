<?php
// src/Event/CommentaireCreatedEvent.php

namespace App\Event;

use App\Entity\Commentaire;
use Symfony\Contracts\EventDispatcher\Event;

class CommentaireCreatedEvent extends Event
{
    private Commentaire $commentaire;

    public function __construct(Commentaire $commentaire)
    {
        $this->commentaire = $commentaire;
    }

    public function getCommentaire(): Commentaire
    {
        return $this->commentaire;
    }
}
