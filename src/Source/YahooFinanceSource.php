<?php
namespace Portfolier\Source;

use GuzzleHttp\Client;

use Portfolier\Source\SourceInterface;
use Portfolier\Factory\YahooFinanceFactory;
use Portfolier\Entity\Stock;
use Portfolier\Entity\Quotations\AbstractQuotations;

class YahooFinanceSource implements SourceInterface
{
    const NAME = "YahooFinance";

    /**
     * @var Portfolier\Factory\YahooFinanceFactory
     */
    protected $factory;

    /**
     * @param Portfolier\Factory\YahooFinanceFactory
     */
    public function __construct(YahooFinanceFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Get response of a Stock quotations from a Source and fabricate a YahooQuotations entity
     *
     * @todo date parameters
     *
     * @param Portfolier\Entity\Stock $stock A Stock entity which contain all necessary information
     * 
     * @return Portfolier\Entity\Quotations\AbstractQuotations
     */
    public function getQuotations(Stock $stock): AbstractQuotations
    {
        $client = new Client();

        $beginnigTimestamp = new \DateTime("-2 years");
        $beginnigTimestamp = $beginnigTimestamp->getTimestamp();

        $endTimestamp = new \DateTime("now");
        $endTimestamp = $endTimestamp->getTimestamp();

        $url = "https://query1.finance.yahoo.com/v8/finance/chart/{$stock->getSymbol()}?interval=1d&period1={$beginnigTimestamp}&period2={$endTimestamp}";

        $response = $client->request('GET', $url, ['verify' => false, 'exceptions' => false])->getBody();

        $quotations = $this->factory->createQuotations($response);
        
        $quotations->setStock($stock);

        return $quotations;
    }

    /**
     * Get the name constant of the source
     *
     * @return string The name constant
     */
    public function getName(): string
    {
        return self::NAME;
    }
}