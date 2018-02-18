<?php
namespace Portfolier\Entity\Quotations;

use Portfolier\Entity\Quotations\AbstractPortfolioQuotations;

class PortfolioQuotations extends AbstractPortfolioQuotations
{
    /**
     * An exchange symbol
     *
     * @var string
     */
    protected $exchange = '';

    /**
     * An array of quotations 
     *
     * @var array
     */
    protected $quotations = [];


    /**
     * Calculate a profit rate of this PortfolioQuotations entity
     *
     * @todo date parameters
     *
     * @return float A profit rate
     */
    public function calculateProfit(): float
    {
        $q = $this->quotations;

        $beginningOfPeriod = $q[count($q) - 1];

        $lastYear = new \DateTime($beginningOfPeriod['date']->format('Y-m-d H:i:s') . "-1 year");

        foreach ($q as $quotation) {
            if ($quotation['date'] == $lastYear) {
                $endOfPeriod = $quotation;
                
                break;
            }
        }

        $profit = ($beginningOfPeriod['close'] - $endOfPeriod['close']) / $endOfPeriod['close'];

        return $profit;
    }

    /**
     * Get an exchange symbol
     *
     * @return string
     */
    public function getExchange(): string
    {
        return $this->exchange;
    }

    /**
     * Set an exchange symbol
     *
     * @param string $string An exchange symbol
     * 
     * @return Portfolier\Entity\Quotations\AbstractPortfolioQuotations
     */
    public function setExchange(string $exchange): AbstractPortfolioQuotations
    {
        $this->exchange = $exchange;

        return $this;
    }

    /**
     * Get an array of quotations 
     * 
     * @return array
     */
    public function getQuotations(): array
    {
        return $this->quotations;
    }

    /**
     * Set an array of quotations
     *
     * @param array $quotations An array of quotations 
     * 
     * @return Portfolier\Entity\Quotations\AbstractPortfolioQuotations
     */
    public function setQuotations(array $quotations): AbstractPortfolioQuotations
    {
        $this->quotations = $quotations;

        return $this;
    }

    /**
     * Set a quotation by the key
     *
     * @param int|string $key A key of a quotation
     * @param array $quotation A quotation array with the data, close, high, low, open, values keys
     * 
     * @return Portfolier\Entity\Quotations\AbstractPortfolioQuotations
     */
    public function setQuotation($key, array $quotation): AbstractPortfolioQuotations
    {
        $this->quotations[$key] = $quotation;

        return $this;
    }
}