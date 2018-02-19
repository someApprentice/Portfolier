<?php
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use Portfolier\Entity\Stock;
use Portfolier\Entity\Quotations\YahooFinanceQuotations;
use Portfolier\Factory\YahooFinanceFactory;
use Portfolier\Source\YahooFinanceSource;

class YahooFinanceSourceTest extends KernelTestCase
{
    public function testGettingQuotations()
    {
        $stock = new Stock();
        $stock->setSymbol("AAPL");
        $stock->setDate();
        
        $factory = new YahooFinanceFactory();
        $source = new YahooFinanceSource($factory);

        $qoutations = $source->getQuotations($stock);

        $this->assertInstanceOf(YahooFinanceQuotations::class, $qoutations);
    }
}