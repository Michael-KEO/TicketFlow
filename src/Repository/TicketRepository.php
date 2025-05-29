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
     * Compte le nombre de tickets pour chaque statut.
     * @return array Un tableau associatif [statutTicket => count]
     */
    public function countTicketsByStatus(): array
    {
        $qb = $this->createQueryBuilder('t')
            // Utiliser la propriété directe 'statutTicket' de l'entité Ticket
            ->select('t.statutTicket, COUNT(t.id) as count')
            ->groupBy('t.statutTicket')
            ->orderBy('t.statutTicket', 'ASC');

        $results = $qb->getQuery()->getResult();

        $counts = [];
        foreach ($results as $result) {
            // S'assurer que la clé n'est pas null si statutTicket peut être null
            $statusKey = $result['statutTicket'] ?? 'Non défini';
            $counts[$statusKey] = $result['count'];
        }
        return $counts;
    }

    /**
     * Compte le nombre de tickets pour chaque priorité.
     * @return array Un tableau associatif [prioriteTicket => count]
     */
    public function countTicketsByPriority(): array
    {
        $qb = $this->createQueryBuilder('t')
            // Utiliser la propriété directe 'prioriteTicket'
            ->select('t.prioriteTicket, COUNT(t.id) as count')
            ->groupBy('t.prioriteTicket')
            ->orderBy('t.prioriteTicket', 'ASC');

        $results = $qb->getQuery()->getResult();

        $counts = [];
        foreach ($results as $result) {
            // S'assurer que la clé n'est pas null si prioriteTicket peut être null
            $priorityKey = $result['prioriteTicket'] ?? 'Non définie';
            $counts[$priorityKey] = $result['count'];
        }
        return $counts;
    }

    /**
     * Compte le nombre de tickets actifs (Nouveau, Ouvert, En cours, Assigné)
     * assignés à l'utilisateur courant (en utilisant le champ 'developpeur').
     * @param UserInterface $user
     * @return int
     */
    public function countCurrentUserActiveTickets(UserInterface $user): int
    {
        $activeStatusNames = ['Nouveau', 'Ouvert', 'En cours', 'Assigné'];

        return (int) $this->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            // Utiliser la propriété 'developpeur' pour l'utilisateur assigné
            ->where('t.developpeur = :user')
            // Utiliser la propriété directe 'statutTicket'
            ->andWhere('t.statutTicket IN (:statusNames)')
            ->setParameter('user', $user)
            ->setParameter('statusNames', $activeStatusNames)
            ->getQuery()
            ->getSingleScalarResult();
    }


    //    /**
    //     * @return Ticket[] Returns an array of Ticket objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Ticket
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}