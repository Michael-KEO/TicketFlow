<?php

namespace App\Controller;

use App\Repository\TicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
final class AdminController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function dashboard(TicketRepository $ticketRepository): Response
    {
        // Date de début (3 mois en arrière)
        $startDate = (new \DateTime())->modify('-3 months');
        $endDate = new \DateTime();

        // Récupération des statistiques
        $ticketsByStatus = $ticketRepository->countTicketsByStatusAndPeriod($startDate, $endDate);
        $ticketsByDev = $ticketRepository->countTicketsByDeveloper();
        $avgResolutionTime = $ticketRepository->getAverageResolutionTime();
        $ticketsEvolution = $ticketRepository->getTicketsCreatedByMonth($startDate, $endDate);

        return $this->render('admin/index.html.twig', [
        'ticketsByStatus' => $ticketsByStatus,
        'ticketsByDev' => $ticketsByDev,
        'avgResolutionTime' => $avgResolutionTime,
        'ticketsEvolution' => $ticketsEvolution,
        'startDate' => $startDate,
        'endDate' => $endDate,
        ]);
    }
}
