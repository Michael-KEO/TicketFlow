<?php

namespace App\Repository;

use App\Entity\Ticket;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<Ticket>
 */
class TicketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ticket::class);
    }

    /**
     * Compte les tickets pour chaque statut.
     */
    public function countTicketsByStatus(): array
    {
        $qb = $this->createQueryBuilder('t')
            ->select('t.statutTicket, COUNT(t.id) as count')
            ->groupBy('t.statutTicket')
            ->orderBy('t.statutTicket', 'ASC');

        $results = $qb->getQuery()->getResult();

        $counts = [];
        foreach ($results as $result) {
            $statusKey = $result['statutTicket'] ?? 'Non défini';
            $counts[$statusKey] = $result['count'];
        }
        return $counts;
    }

    /**
     * Compte les tickets pour chaque priorité.
     */
    public function countTicketsByPriority(): array
    {
        $qb = $this->createQueryBuilder('t')
            ->select('t.prioriteTicket, COUNT(t.id) as count')
            ->groupBy('t.prioriteTicket')
            ->orderBy('t.prioriteTicket', 'ASC');

        $results = $qb->getQuery()->getResult();

        $counts = [];
        foreach ($results as $result) {
            $priorityKey = $result['prioriteTicket'] ?? 'Non définie';
            $counts[$priorityKey] = $result['count'];
        }
        return $counts;
    }

    /**
     * Tickets actifs assignés à un développeur.
     */
    public function countCurrentUserActiveTickets(UserInterface $user): int
    {
        $activeStatusNames = ['Nouveau', 'Ouvert', 'En cours', 'Assigné'];

        return (int) $this->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->where('t.developpeur = :user')
            ->andWhere('t.statutTicket IN (:statusNames)')
            ->setParameter('user', $user)
            ->setParameter('statusNames', $activeStatusNames)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * ✅ Compte les tickets par statut pour un développeur.
     */
    public function countTicketsByStatusForUser(UserInterface $user, ?string $status = null): int
    {
        $qb = $this->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->where('t.developpeur = :user')
            ->setParameter('user', $user);

        if ($status !== null) {
            $qb->andWhere('t.statutTicket = :status')
               ->setParameter('status', $status);
        }

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * ✅ Derniers tickets assignés à un développeur.
     */
    public function findRecentTicketsForUser(UserInterface $user, int $limit = 5): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.developpeur = :user')
            ->setParameter('user', $user)
            ->orderBy('t.dateCreation', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * ✅ Compte tous les tickets créés par un rapporteur.
     */
    public function countTicketsCreatedByUser(UserInterface $user): int
    {
        return (int) $this->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->where('t.rapporteur = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * ✅ Compte les tickets créés par un rapporteur pour un statut donné.
     */
    public function countTicketsByStatusCreatedByUser(UserInterface $user, string $status): int
    {
        return (int) $this->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->where('t.rapporteur = :user')
            ->andWhere('t.statutTicket = :status')
            ->setParameter('user', $user)
            ->setParameter('status', $status)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * ✅ Derniers tickets créés par un rapporteur.
     */
    public function findRecentTicketsCreatedByUser(UserInterface $user, int $limit = 5): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.rapporteur = :user')
            ->setParameter('user', $user)
            ->orderBy('t.dateCreation', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
