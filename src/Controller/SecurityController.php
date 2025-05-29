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
        
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Si l'utilisateur vient de cliquer sur "Changer de rôle", on efface le rôle actuel
        if ($request->query->get('change') === '1') {
            $request->getSession()->remove('active_role');
        }

        // Vérifier si un rôle actif est déjà enregistré (et qu'on ne force pas le changement)
        $activeRole = $request->getSession()->get('active_role');
        
        if ($activeRole && $request->query->get('change') !== '1') {
            return $this->redirectToRoute($this->getDashboardRoute($activeRole));
        }
        
        $roles = $user->getRoles();

        // Si l'utilisateur n'a qu'un seul rôle (autre que ROLE_USER), on le sélectionne automatiquement
        $availableRoles = array_filter($roles, function($role) {
            return $role !== 'ROLE_USER';
        });

        if (count($availableRoles) === 1) {
            $singleRole = array_values($availableRoles)[0];
            $request->getSession()->set('active_role', $singleRole);
            return $this->redirectToRoute($this->getDashboardRoute($singleRole));
        }

        // Afficher l'interface de choix de rôle
        return $this->render('security/choose_role.html.twig', [
            'roles' => $roles,
        ]);
    }

    #[Route('/select-role', name: 'select_role', methods: ['POST'])]
    public function selectRole(Request $request): Response
    {
        $role = $request->request->get('role');

        if (!$role) {
            throw new \InvalidArgumentException('Aucun rôle sélectionné.');
        }

        // Vérifier que l'utilisateur a bien ce rôle
        $user = $this->getUser();
        if (!$user || !in_array($role, $user->getRoles())) {
            throw $this->createAccessDeniedException('Vous n\'avez pas ce rôle.');
        }

        // Stocker le rôle dans la session
        $request->getSession()->set('active_role', $role);

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