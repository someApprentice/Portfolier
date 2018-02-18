<?php
namespace Portfolier\Source;

use GuzzleHttp\Client;

use Portfolier\Source\SourceInterface;
use Portfolier\Factory\GoogleFinanceFactory;
use Portfolier\Entity\Stock;
use Portfolier\Entity\Quotations\AbstractQuotations;

class GoogleFinanceSource implements SourceInterface
{
    protected $factory;

    public function __construct(GoogleFinanceFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Get response of a Stock quotations from a Source and fabricate a GoogleQuotations entity
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

        $response = $client->request('GET', "https://finance.google.com/finance/getprices?p=2Y&i=86400&f=d,o,h,l,c,v&q={$stock->getSymbol()}", ['verify' => false])->getBody();

        $quotations = $this->factory->createQuotations($response);
        
        $quotations->setStock($stock);

        return $quotations;
    }
}