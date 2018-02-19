<?php
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use Portfolier\Entity\Stock;
use Portfolier\Entity\Quotations\GoogleFinanceQuotations;
use Portfolier\Factory\GoogleFinanceFactory;
use Portfolier\Source\GoogleFinanceSource;

class GoogleFinanceSourceTest extends KernelTestCase
{
    public function testGettingQuotations()
    {
        $stock = new Stock();
        $stock->setSymbol("AAPL");
        $stock->setDate();
        
        $factory = new GoogleFinanceFactory();
        $source = new GoogleFinanceSource($factory);

        $qoutations = $source->getQuotations($stock);

        $this->assertInstanceOf(GoogleFinanceQuotations::class, $qoutations);
    }
}