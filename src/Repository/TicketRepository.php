<?php

namespace App\Repository;

use App\Entity\Ticket;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Query\Expr\Join;


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

    /**
     * Récupère le nombre de tickets par statut sur une période donnée
     */
    public function countTicketsByStatusAndPeriod(\DateTime $startDate, \DateTime $endDate): array
    {
        return $this->createQueryBuilder('t')
            ->select('t.statutTicket, COUNT(t.id) as count')
            ->where('t.dateCreation BETWEEN :start AND :end')
            ->setParameter('start', $startDate)
            ->setParameter('end', $endDate)
            ->groupBy('t.statutTicket')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère la répartition des tickets par développeur
     */
    public function countTicketsByDeveloper(): array
    {
        return $this->createQueryBuilder('t')
            ->select('u.nom, u.prenom, COUNT(t.id) as count')
            ->leftJoin('t.developpeur', 'u')
            ->where('t.developpeur IS NOT NULL')
            ->groupBy('u.id')
            ->getQuery()
            ->getResult();
    }

    /**
     * Calcule le délai moyen de résolution des tickets (en jours)
     */
    public function getAverageResolutionTime(): float|null
    {
        // On récupère d'abord les tickets résolus
        $tickets = $this->createQueryBuilder('t')
            ->select('t.dateCreation', 't.dateResolution')
            ->where('t.dateCreation IS NOT NULL')
            ->andWhere('t.dateResolution IS NOT NULL')
            ->getQuery()
            ->getResult();

        if (empty($tickets)) {
            return null;
        }

        // Puis on calcule manuellement la différence en jours
        $totalDays = 0;
        $count = count($tickets);

        foreach ($tickets as $ticket) {
            $interval = $ticket['dateCreation']->diff($ticket['dateResolution']);
            $totalDays += $interval->days;
        }

        return $count > 0 ? $totalDays / $count : null;
    }


    /**
     * Récupère les données pour un graphique de l'évolution des tickets par mois
     */
    public function getTicketsCreatedByMonth(\DateTime $startDate, \DateTime $endDate): array
    {
        $queryBuilder = $this->createQueryBuilder('t')
            ->select('t.dateCreation')
            ->where('t.dateCreation BETWEEN :start AND :end')
            ->setParameter('start', $startDate)
            ->setParameter('end', $endDate)
            ->getQuery();

        $results = $queryBuilder->getResult();

        $monthlyCounts = [];
        foreach ($results as $row) {
            $date = $row['dateCreation'];
            $year = $date->format('Y');
            $month = $date->format('m');
            $key = $year . '-' . $month;

            if (!isset($monthlyCounts[$key])) {
                $monthlyCounts[$key] = [
                    'year' => (int)$year,
                    'month' => (int)$month,
                    'count' => 0,
                ];
            }
            $monthlyCounts[$key]['count']++;
        }

        // Sort results by year and month
        uksort($monthlyCounts, function($a, $b) {
            return strtotime($a . '-01') - strtotime($b . '-01');
        });

        return array_values($monthlyCounts);
    }
}
