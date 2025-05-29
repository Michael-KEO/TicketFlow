<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[IsGranted('ROLE_DEV')]
final class DeveloperController extends AbstractController
{
    #[Route('/developer/dashboard', name: 'developer_dashboard')]
    public function dashboard(): Response
    {
        return new Response('<h1>Bienvenue Développeur 👨‍💻</h1>');
    }
}
