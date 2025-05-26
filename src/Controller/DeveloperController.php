<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DeveloperController extends AbstractController
{
    #[Route('/developer/dashboard', name: 'developer_dashboard')]
    #[IsGranted('ROLE_DEVELOPER')]
        public function dashboard(): Response
            {
                return new Response('<h1>Bienvenue Développeur 👨‍💻</h1>');
            }
}
