<?php

namespace App\Repository;

use App\Entity\Commentaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commentaire>
 */
class CommentaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commentaire::class);
    }

    /**
     * Trouve tous les commentaires d'un ticket triés par date (plus récent en premier)
     */
    public function findByTicketOrderedByDate(int $ticketId): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.ticket = :ticketId')
            ->setParameter('ticketId', $ticketId)
            ->orderBy('c.dateCreation', 'DESC')
            ->getQuery()
            ->getResult();
    }
}