<?php
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use Portfolier\Entity\Stock;
use Portfolier\Entity\Quotations\YahooFinanceQuotations;
use Portfolier\Factory\YahooFinanceFactory; //remove to collection
use Portfolier\Source\YahooFinanceSource; //remove to collection

class YahooFinanceSourceTest extends KernelTestCase
{
    public function testGettingQuotations()
    {
        $stock = new Stock();
        $stock->setSymbol("AAPL");
        $stock->setDate();
        
        $factory = new YahooFinanceFactory(); //remove to collection
        $source = new YahooFinanceSource($factory); //remove to collection

        $qoutations = $source->getQuotations($stock);

        $this->assertInstanceOf(YahooFinanceQuotations::class, $qoutations);
    }
}