<?php
namespace Portfolier\Tests\Util;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use Doctrine\ORM\EntityManager;

use Portfolier\Entity\User;
use Portfolier\Service\Authorizer;

class AuthorizationTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    /**
     * @runInSeparateProcess
     */
    public function testRegistration()
    {
        $em = $this->em;

        $faker = \Faker\Factory::create();

        $email = $faker->email;
        $name = $faker->firstName;
        $password = $faker->password;

        $user = new User();
        $user->setEmail($email);
        $user->setName($name);
        $user->setPassword($password);

        $authorizer = new Authorizer($em);

        $authorizer->register($user);

        $repository = $em->getRepository(User::class);

        $user = $repository->findOneBy(['email' => $email]);

        $this->assertInstanceOf(User::class, $user);
    }

    /**
     * @runInSeparateProcess
     */
    public function testLogin()
    {
        $em = $this->em;

        $faker = \Faker\Factory::create();

        $email = $faker->email;
        $name = $faker->firstName;
        $password = $faker->password;

        $user = new User();
        $user->setEmail($email);
        $user->setName($name);
        $user->setPassword($password);

        $authorizer = new Authorizer($em);

        $authorizer->register($user);

        $user = new User();
        $user->setEmail($email);
        $user->setPassword($password);

        $user = $authorizer->login($user);

        $this->assertInstanceOf(User::class, $user);
    }
}