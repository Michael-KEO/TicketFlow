<?php

namespace App\Controller;

use App\Repository\TicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Core\User\UserInterface;

#[IsGranted('ROLE_USER')]
final class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(Request $request, TicketRepository $ticketRepository): Response
    {
        $user = $this->getUser();
        $activeRole = $request->getSession()->get('active_role');

        if (!$activeRole) {
            return $this->redirectToRoute('homepage');
        }

        return match ($activeRole) {
            'ROLE_ADMIN' => $this->render('admin/index.html.twig'),

            'ROLE_DEV' => $this->render('developer/index.html.twig', [
                'statistiques' => [
                    'total' => $ticketRepository->countTicketsByStatusForUser($user, null),
                    'nouveau' => $ticketRepository->countTicketsByStatusForUser($user, 'Nouveau'),
                    'assigne' => $ticketRepository->countTicketsByStatusForUser($user, 'Assigné'),
                    'en_cours' => $ticketRepository->countTicketsByStatusForUser($user, 'En cours'),
                    'resolu' => $ticketRepository->countTicketsByStatusForUser($user, 'Résolu'),
                ],
                'mesTickets' => $ticketRepository->findRecentTicketsForUser($user),
            ]),

            'ROLE_RAPPORTEUR' => $this->render('reporter/index.html.twig', [
                'statistiques' => [
                    'total' => $ticketRepository->countTicketsCreatedByUser($user),
                    'nouveau' => $ticketRepository->countTicketsByStatusCreatedByUser($user, 'Nouveau'),
                    'en_cours' => $ticketRepository->countTicketsByStatusCreatedByUser($user, 'En cours'),
                    'resolu' => $ticketRepository->countTicketsByStatusCreatedByUser($user, 'Résolu'),
                    'ferme' => $ticketRepository->countTicketsByStatusCreatedByUser($user, 'Fermé'),
                ],
                'mesTickets' => $ticketRepository->findRecentTicketsCreatedByUser($user),
            ]),

            default => $this->redirectToRoute('homepage'),
        };
    }
}
