<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DeveloperController extends AbstractController
{
    #[Route('/developer/dashboard', name: 'developer_dashboard')]
    public function dashboard(Request $request): Response
    {
        $activeRole = $request->getSession()->get('active_role');
        
        if ($activeRole !== 'ROLE_DEV') {
            throw $this->createAccessDeniedException('Accès interdit - Rôle requis: ROLE_DEV');
        }

        return $this->render('developer/index.html.twig', [
    'controller_name' => 'DeveloperController',
]);

    }
}
