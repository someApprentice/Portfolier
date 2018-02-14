<?php
namespace Portfolier\Service;

use Doctrine\ORM\EntityManager;

use Portfolier\Entity\User;

class Authorizer
{
    /**
     * A Doctrine Entity Manager
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
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

        $salt = $this->generateSalt();
        $hash = $this->generateHash($user->getPassword(), $salt);

        $user->setSalt($salt);
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

        if ($u->getHash() === $this->generateHash($user->getPassword(), $u->getSalt())) {
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
        if ($this->isLogedIn()) {
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
     * @return void
     */
    public function createCookies(User $user)
    {
        $expires = 60 * 60 * 24 * 30 * 12 * 3;

        setcookie('id', $user->getId(), time() + $expires, '/', null, null, true);
        setcookie('hash', $user->getHash(), time() + $expires, '/', null, null, true);
    }

    /**
     * Generate salt for hashing a password
     * 
     * @return string
     */
    public function generateSalt(): string
    {
        $salt = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ.*-^%$#@!?%&%_=+<>[]{}0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ.*-^%$#@!?%&%_=+<>[]{}'), 0, 44);
        
        return $salt;
    }

    /**
     * Hashing a password with a salt
     *
     * @param string $password A password
     * @param string $salt A salt
     * 
     * @return string
     */
    public static function generateHash(string $password, string $salt): string
    {
        return md5($password . $salt);
    }
}