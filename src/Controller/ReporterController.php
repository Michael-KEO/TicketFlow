<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[IsGranted('ROLE_RAPPORTEUR')]
final class ReporterController extends AbstractController
{
    #[Route('/reporter/dashboard', name: 'reporter_dashboard')]
    public function dashboard(): Response
    {
        return new Response('<h1>Bienvenue Rapporteur 📝</h1>');
    }
}
