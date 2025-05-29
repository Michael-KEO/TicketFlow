<?php

namespace App\Controller;

use App\Repository\TicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')] // Ou un rôle plus spécifique si nécessaire
final class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(TicketRepository $ticketRepository): Response
    {
        $user = $this->getUser(); // Pour les statistiques spécifiques à l'utilisateur

        // TODO: Appeler les méthodes du TicketRepository pour récupérer les données
        $ticketsParStatut = $ticketRepository->countTicketsByStatus();
        $ticketsParPriorite = $ticketRepository->countTicketsByPriority();
        $mesTicketsEnCours = 0; // Valeur par défaut
        if ($user instanceof UserInterface) { // S'assurer que $user est bien un UserInterface
            $mesTicketsEnCours = $ticketRepository->countCurrentUserActiveTickets($user);
        }


        // TODO: Passer les données au template
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'ticketsParStatut' => $ticketsParStatut,
            'ticketsParPriorite' => $ticketsParPriorite,
            'mesTicketsEnCours' => $mesTicketsEnCours, // Passer cette nouvelle variable
        ]);
    }
}
