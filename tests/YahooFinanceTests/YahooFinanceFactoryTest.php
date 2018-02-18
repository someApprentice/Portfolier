<?php
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use GuzzleHttp\Client;

use Portfolier\Entity\Stock;
use Portfolier\Entity\Quotations\YahooFinanceQuotations;
use Portfolier\Factory\YahooFinanceFactory;

class YahooFinanceFactoryTest extends KernelTestCase
{
    public function testCreatingQuotations()
    {
        $stock = new Stock();
        $stock->setSymbol("AAPL");
        $stock->setDate();

        $factory = new YahooFinanceFactory();

        $client = new Client();

        $beginnigTimestamp = new \DateTime("-2 years");
        $beginnigTimestamp = $beginnigTimestamp->getTimestamp();

        $endTimestamp = new \DateTime("now");
        $endTimestamp = $endTimestamp->getTimestamp();

        $url = "https://query1.finance.yahoo.com/v8/finance/chart/{$stock->getSymbol()}?interval=1d&period1={$beginnigTimestamp}&period2={$endTimestamp}";

        $response = $client->request('GET', $url, ['verify' => false, 'exceptions' => false])->getBody();

        $qoutations = $factory->createQuotations($response);

        $this->assertInstanceOf(YahooFinanceQuotations::class, $qoutations);
    }
}