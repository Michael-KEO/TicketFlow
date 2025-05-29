<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/choose-role', name: 'choose_role')]
    public function chooseRole(Request $request): Response
    {
        $user = $this->getUser();
        $roles = $user->getRoles();

        if (count($roles) === 1) {
            return $this->redirectToRoute($this->getDashboardRoute($roles[0]));
        }

        return $this->render('security/choose_role.html.twig', [
            'roles' => $roles,
        ]);
    }

#[Route('/select-role', name: 'select_role', methods: ['POST'])]
public function selectRole(Request $request): Response
{
    $role = $request->request->get('role');

    return $this->redirectToRoute($this->getDashboardRoute($role));
}


    private function getDashboardRoute(string $role): string
    {
        return match ($role) {
            'ROLE_ADMIN' => 'admin_dashboard',
            'ROLE_DEV' => 'developer_dashboard',
            'ROLE_RAPPORTEUR' => 'reporter_dashboard',
            default => 'app_login',
        };
    }
}
