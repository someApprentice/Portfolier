<?php
namespace Portfolier\Tests\Util;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use Doctrine\ORM\EntityManager;

use Portfolier\Entity\Portfolio;
use Portfolier\Entity\Stock;
use Portfolier\Entity\User;
use Portfolier\Service\Portfolier;
use Portfolier\Service\Authorizer;

class PortfolierTest extends KernelTestCase
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
    public function testGettingUserPortfolios()
    {
        $em = $this->em;

        $portfolier = new Portfolier($em);

        $authorizer = new Authorizer($em);

        $faker = \Faker\Factory::create();

        $user = new User();
        $user->setEmail($faker->email);
        $user->setName($faker->userName);
        $user->setPassword($faker->password);

        $user = $authorizer->register($user);

        for ($i = 0; $i < 3; $i++) {
            $faker = \Faker\Factory::create();

            $portfolio = new Portfolio();
            $portfolio->setName($faker->word);
            $portfolio->setUser($user);

            $portfolier->createPortfolio($portfolio);
        }

        $portfolios = $portfolier->getUserPortfolios($user);

        $this->assertInternalType('array', $portfolios);

        for ($i = 0; $i < 3; $i++) {
            $this->assertInstanceOf(Portfolio::class, $portfolios[$i]);
        }
    }

    /**
     * @runInSeparateProcess
     */
    public function testCreatingPortfolio()
    {
        $em = $this->em;

        $portfolier = new Portfolier($em);

        $authorizer = new Authorizer($em);

        $faker = \Faker\Factory::create();

        $user = new User();
        $user->setEmail($faker->email);
        $user->setName($faker->userName);
        $user->setPassword($faker->password);

        $user = $authorizer->register($user);

        $portfolio = new Portfolio();
        $portfolio->setName($faker->word);
        $portfolio->setUser($user);

        $portfolio = $portfolier->createPortfolio($portfolio);

        $portfolio = $portfolier->getPortfolio($portfolio->getId());

        $this->assertInstanceOf(Portfolio::class, $portfolio);
    }

    /**
     * @runInSeparateProcess
     */
    public function testRemovingPortfolio()
    {
        $em = $this->em;

        $portfolier = new Portfolier($em);

        $authorizer = new Authorizer($em);

        $faker = \Faker\Factory::create();

        $user = new User();
        $user->setEmail($faker->email);
        $user->setName($faker->userName);
        $user->setPassword($faker->password);

        $user = $authorizer->register($user);

        $portfolio = new Portfolio();
        $portfolio->setName($faker->word);
        $portfolio->setUser($user);

        $portfolio = $portfolier->createPortfolio($portfolio);

        $id = $portfolio->getId();

        $portfolier->removePortfolio($portfolio);

        $portfolio = $portfolier->getPortfolio($id);

        $this->assertNull($portfolio);
    }

    /**
     * @runInSeparateProcess
     */
    public function testGettingPortfolioStocks()
    {
        $em = $this->em;

        $portfolier = new Portfolier($em);

        $authorizer = new Authorizer($em);

        $faker = \Faker\Factory::create();

        $user = new User();
        $user->setEmail($faker->email);
        $user->setName($faker->userName);
        $user->setPassword($faker->password);

        $user = $authorizer->register($user);

        $portfolio = new Portfolio();
        $portfolio->setName($faker->word);
        $portfolio->setUser($user);

        $portfolio = $portfolier->createPortfolio($portfolio);

        $aapl = new Stock();
        $aapl->setPortfolio($portfolio);
        $aapl->setSymbol("AAPL");
        $aapl->setDate();

        $goog = new Stock();
        $goog->setPortfolio($portfolio);
        $goog->setSymbol("GOOG");
        $goog->setDate();

        $yahoo = new Stock();
        $yahoo->setPortfolio($portfolio);
        $yahoo->setSymbol("YAHOF");
        $yahoo->setDate();

        $stocks = [$aapl, $goog, $yahoo];

        foreach ($stocks as $stock) {
            $portfolier->addStock($stock);
        }

        $stocks = $portfolier->getPortfolioStocks($portfolio);

        $this->assertInternalType('array', $stocks);

        for ($i = 0; $i < 3; $i++) {
            $this->assertInstanceOf(Stock::class, $stocks[$i]);
        }
    }

    /**
     * @runInSeparateProcess
     */
    public function testAddingStockToPortfolio()
    {
        $em = $this->em;

        $portfolier = new Portfolier($em);

        $authorizer = new Authorizer($em);

        $faker = \Faker\Factory::create();

        $user = new User();
        $user->setEmail($faker->email);
        $user->setName($faker->userName);
        $user->setPassword($faker->password);

        $user = $authorizer->register($user);

        $portfolio = new Portfolio();
        $portfolio->setName($faker->word);
        $portfolio->setUser($user);

        $portfolio = $portfolier->createPortfolio($portfolio);

        $aapl = new Stock();
        $aapl->setPortfolio($portfolio);
        $aapl->setSymbol("AAPL");
        $aapl->setDate();

        $stock = $portfolier->addStock($aapl);

        $id = $stock->getId();

        $stock = $portfolier->getStock($id);

        $this->assertInstanceOf(Stock::class, $stock);
    }

    /**
     * @runInSeparateProcess
     */
    public function testRemovingStock()
    {
        $em = $this->em;

        $portfolier = new Portfolier($em);

        $authorizer = new Authorizer($em);

        $faker = \Faker\Factory::create();

        $user = new User();
        $user->setEmail($faker->email);
        $user->setName($faker->userName);
        $user->setPassword($faker->password);

        $user = $authorizer->register($user);

        $portfolio = new Portfolio();
        $portfolio->setName($faker->word);
        $portfolio->setUser($user);

        $portfolio = $portfolier->createPortfolio($portfolio);

        $aapl = new Stock();
        $aapl->setPortfolio($portfolio);
        $aapl->setSymbol("AAPL");
        $aapl->setDate();

        $stock = $portfolier->addStock($aapl);

        $id = $stock->getId();

        $stock = $portfolier->removeStock($stock);

        $this->assertNull($stock);
    }
}