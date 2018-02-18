<?php
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use Portfolier\Entity\Stock;
use Portfolier\Entity\Quotations\GoogleFinanceQuotations;
use Portfolier\Factory\GoogleFinanceFactory; //remove to collection
use Portfolier\Source\GoogleFinanceSource; //remove to collection

class GoogleFinanceSourceTest extends KernelTestCase
{
    public function testGettingQuotations()
    {
        $stock = new Stock();
        $stock->setSymbol("AAPL");
        $stock->setDate();
        
        $factory = new GoogleFinanceFactory(); //remove to collection
        $source = new GoogleFinanceSource($factory); //remove to collection

        $qoutations = $source->getQuotations($stock);

        $this->assertInstanceOf(GoogleFinanceQuotations::class, $qoutations);
    }
}