<?php
namespace Portfolier\Service;

use Doctrine\ORM\EntityManagerInterface;

use Portfolier\Entity\User;

class Authorizer
{
    /**
     * A Doctrine Entity Manager
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Create a new User in the database
     *
     * @param Portfolier\Entity\User $u An User entity
     *
     * @return Portfolier\Entity\User
     *
     * @throws \Exception If the User already exist in a database
     */
    public function register(User $user)
    {
        if ($this->isExist($user)) {
            throw new \Exception("This User is already exist");
        }

        $hash = password_hash($user->getPlainPassword(), \PASSWORD_DEFAULT);

        $user->setPassword($hash);

        $this->em->persist($user);

        $this->em->flush();

        return $user;
    }

    /**
     * Check if User exist in a database
     *
     * @return bool
     */
    public function isExist(User $user)
    {
        $u = $this->em->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);

        if ($u) {
            return true;
        }

        return false;
    }
}