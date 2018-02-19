<?php
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use Doctrine\ORM\EntityManager;


use Portfolier\Collection\SourceCollection;
use Portfolier\Service\Portfolier;
use Portfolier\Service\Authorizer;
use Portfolier\Entity\User;
use Portfolier\Entity\Portfolio;
use Portfolier\Entity\Stock;
use Portfolier\Entity\Quotations\PortfolioQuotations;

class PortfolioQuotationTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $sources;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->sources = $kernel->getContainer()->get(SourceCollection::class);
    }

    /**
     * @runInSeparateProcess
     */
    public function testCalculatingProfit()
    {
        $sources = $this->sources;

        $portfolio = new Portfolio();

        $stock = new Stock();
        $stock->setPortfolio($portfolio);
        $stock->setSymbol("AAPL");
        $stock->setDate();

        $portfolio->setStocks([$stock]);

        foreach ($sources->getSources() as $source) {
            $quotations = $portfolio->getQuotations($source);

            foreach ($quotations as $q) {
                $profit = $q->calculateProfit();

                $this->assertInternalType('float', $profit);
            }
        }
    }

    /**
     * @runInSeparateProcess
     */
    public function testCalculatingPortolioQuotations()
    {
        $sources = $this->sources;

        $portfolio = new Portfolio();

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

        $sber = new Stock();
        $sber->setPortfolio($portfolio);
        $sber->setSymbol("SBER");
        $sber->setDate();

        $gazp = new Stock();
        $gazp->setPortfolio($portfolio);
        $gazp->setSymbol("GAZP");
        $gazp->setDate();

        $stocks = [$aapl, $goog, $yahoo, $sber, $gazp];

        $portfolio->setStocks($stocks);

        foreach ($sources->getSources() as $key => $source) {
            $quotations = $portfolio->getQuotations($source);

            foreach ($quotations as $exchange => $q) {
                $this->assertInstanceOf(PortfolioQuotations::class, $q);
            }
        }
    }
}