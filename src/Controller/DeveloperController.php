<?php

namespace App\Controller;

use App\Repository\TicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DeveloperController extends AbstractController
{
    #[Route('/developer/dashboard', name: 'developer_dashboard')]
    public function dashboard(Request $request, TicketRepository $ticketRepository): Response
    {
        $activeRole = $request->getSession()->get('active_role');
        
        if ($activeRole !== 'ROLE_DEV') {
            throw $this->createAccessDeniedException('Accès interdit - Rôle requis: ROLE_DEV');
        }

        $user = $this->getUser();
        
        // Statistiques pour le développeur
        $mesTickets = $ticketRepository->findBy(['developpeur' => $user], ['dateCreation' => 'DESC'], 5);
        $totalTickets = $ticketRepository->count(['developpeur' => $user]);
        
        $statistiques = [
            'total' => $totalTickets,
            'nouveau' => $ticketRepository->count(['developpeur' => $user, 'statutTicket' => 'Nouveau']),
            'assigne' => $ticketRepository->count(['developpeur' => $user, 'statutTicket' => 'Assigné']),
            'en_cours' => $ticketRepository->count(['developpeur' => $user, 'statutTicket' => 'En cours']),
            'resolu' => $ticketRepository->count(['developpeur' => $user, 'statutTicket' => 'Résolu']),
        ];

        return $this->render('developer/index.html.twig', [
            'controller_name' => 'DeveloperController',
            'mesTickets' => $mesTickets,
            'statistiques' => $statistiques
        ]);
    }
}