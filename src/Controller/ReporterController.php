<?php

namespace App\Controller;

use App\Repository\TicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ReporterController extends AbstractController
{
    #[Route('/reporter/dashboard', name: 'reporter_dashboard')]
    public function dashboard(Request $request, TicketRepository $ticketRepository): Response
    {
        $activeRole = $request->getSession()->get('active_role');
        
        if ($activeRole !== 'ROLE_RAPPORTEUR') {
            throw $this->createAccessDeniedException('Accès interdit - Rôle requis: ROLE_RAPPORTEUR');
        }

        $user = $this->getUser();
        
        // Statistiques pour le rapporteur
        $mesTickets = $ticketRepository->findBy(['rapporteur' => $user], ['dateCreation' => 'DESC'], 5);
        $totalTickets = $ticketRepository->count(['rapporteur' => $user]);
        
        $statistiques = [
            'total' => $totalTickets,
            'nouveau' => $ticketRepository->count(['rapporteur' => $user, 'statutTicket' => 'Nouveau']),
            'en_cours' => $ticketRepository->count(['rapporteur' => $user, 'statutTicket' => 'En cours']),
            'resolu' => $ticketRepository->count(['rapporteur' => $user, 'statutTicket' => 'Résolu']),
            'ferme' => $ticketRepository->count(['rapporteur' => $user, 'statutTicket' => 'Fermé'])
        ];

        return $this->render('reporter/index.html.twig', [
            'controller_name' => 'ReporterController',
            'mesTickets' => $mesTickets,
            'statistiques' => $statistiques
        ]);
    }
}