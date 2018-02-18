<?php
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use Doctrine\ORM\EntityManager;

use Portfolier\Service\Portfolier;
use Portfolier\Service\Authorizer;
use Portfolier\Entity\User;
use Portfolier\Entity\Portfolio;
use Portfolier\Entity\Stock;
use Portfolier\Entity\Quotations\GoogleFinanceQuotations;
use Portfolier\Factory\GoogleFinanceFactory; //remove to collection
use Portfolier\Source\GoogleFinanceSource; //remove to collection

class PortfolioQuotationTest extends KernelTestCase
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
    public function testCalculatingProfit()
    {
        $em = $this->em;

        $portfolier = new Portfolier($em);

        $portfolio = new Portfolio();

        $stock = new Stock();
        $stock->setPortfolio($portfolio);
        $stock->setSymbol("AAPL");
        $stock->setDate();

        $portfolio->setStocks([$stock]);

        $quotations = $portfolier->getQuotations($portfolio);

        foreach ($quotations as $q) {
            $profit = $q->calculateProfit();

            $this->assertInternalType('float', $profit);
        }
    }
}