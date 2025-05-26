<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ReporterController extends AbstractController
{
    #[Route('/reporter/dashboard', name: 'reporter_dashboard')]
    #[IsGranted('ROLE_RAPPORTEUR')]
        public function dashboard(): Response
            {
                return new Response('<h1>Bienvenue Rapporteur 📝</h1>');
            }
}
