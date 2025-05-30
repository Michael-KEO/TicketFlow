<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * Retourne tous les utilisateurs qui ont un rôle spécifique (ex : ROLE_DEV)
     */
// src/Repository/UserRepository.php

// src/Repository/UserRepository.php

public function findUsersByRole(string $role): array
{
    $users = $this->createQueryBuilder('u')
        ->getQuery()
        ->getResult();

    // Ne garde que ceux qui ont effectivement le rôle dans le tableau JSON
    return array_filter($users, function (User $user) use ($role) {
        return in_array($role, $user->getRoles(), true);
    });
}


}
