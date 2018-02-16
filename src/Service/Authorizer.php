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
     * @return mixed
     */
    public function register(User $user)
    {
        $u = $this->em->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);

        if ($u) {
            return false;
        }

        $hash = password_hash($user->getPassword(), \PASSWORD_DEFAULT);

        $user->setHash($hash);

        $this->em->persist($user);

        $this->em->flush();

        $this->login($user);

        return $user;
    }

    /**
     * Checks if the User exist and if its true login him
     *
     * @param Portfolier\Entity\User $user An User entity
     * 
     * @return mixed
     */
    public function login(User $user)
    {
        $u = $this->em->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);

        if (!$u) {
            return false;
        }

        if (password_verify($user->getPassword(), $u->getHash())) {
            $this->createCookies($u);

            return $u;
        }

        return false;
    }

    /**
     * Logout User
     *
     * @return bool
     */
    public function logout(): bool
    {
        if ($this->isLoggedIn()) {
            setcookie('id', null, time() - 1, '/');
            setcookie('hash', null, time() - 1, '/');

            return true;
        }

        return false;
    }

    /**
     * Get Logged User
     *
     * @return mixed
     */
    public function getLogged()
    {
        if ($this->isLoggedIn()) {
            $user = $this->em->getRepository(User::class)->find($_COOKIE['id']);

            return $user;
        }

        return false;
    }

    /**
     * Check if User loged in
     *
     * @return bool
     */
    public function isLoggedIn(): bool
    {
        if (isset($_COOKIE['id'])) {
            $user = $this->em->getRepository(User::class)->find($_COOKIE['id']);

            if (isset($_COOKIE['hash'])) {
                if ($user->getHash() == $_COOKIE['hash']) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Create an User cookies
     *
     * @param Portfolier\Entity\User $user An User entity
     *
     * @return void
     */
    public function createCookies(User $user)
    {
        $expires = 60 * 60 * 24 * 30 * 12 * 3;

        setcookie('id', $user->getId(), time() + $expires, '/', null, null, true);
        setcookie('hash', $user->getHash(), time() + $expires, '/', null, null, true);
    }
}