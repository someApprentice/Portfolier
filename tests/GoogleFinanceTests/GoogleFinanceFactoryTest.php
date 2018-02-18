<?php
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use GuzzleHttp\Client;

use Portfolier\Entity\Stock;
use Portfolier\Entity\Quotations\GoogleFinanceQuotations;
use Portfolier\Factory\GoogleFinanceFactory;

class GoogleFinanceFactoryTest extends KernelTestCase
{
    public function testCreatingQuotations()
    {
        $stock = new Stock();
        $stock->setSymbol("AAPL");
        $stock->setDate();

        $factory = new GoogleFinanceFactory();

        $client = new Client();

        $response = $client->request('GET', "https://finance.google.com/finance/getprices?p=2Y&i=86400&f=d,o,h,l,c,v&q={$stock->getSymbol()}", ['verify' => false])->getBody();

        $qoutations = $factory->createQuotations($response);

        $this->assertInstanceOf(GoogleFinanceQuotations::class, $qoutations);
    }
}