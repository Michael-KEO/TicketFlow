<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ReporterController extends AbstractController
{
    #[Route('/reporter/dashboard', name: 'reporter_dashboard')]
    public function dashboard(Request $request): Response
    {
        $activeRole = $request->getSession()->get('active_role');
        
        if ($activeRole !== 'ROLE_RAPPORTEUR') {
            throw $this->createAccessDeniedException('Accès interdit - Rôle requis: ROLE_RAPPORTEUR');
        }

        return $this->render('reporter/index.html.twig', [
    'controller_name' => 'ReporterController',
]);

    }

}
