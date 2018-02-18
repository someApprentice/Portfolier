<?php
namespace Portfolier\Entity\Quotations;

abstract class AbstractPortfolioQuotations
{
    /**
     * An exchange symbol
     *
     * @var string
     */
    protected $exchange;

    /**
     * An array of quotations 
     *
     * @var array
     */
    protected $quotations;

    /**
     * Calculate a profit rate of this AbstractPortfolioQuotations entity
     *
     * @todo date parameters
     *
     * @return float A profit rate
     */
    abstract public function calculateProfit(): float;

    /**
     * Get an exchange symbol
     *
     * @return string
     */
    abstract public function getExchange(): string;

    /**
     * Set an exchange symbol
     *
     * @param string $string An exchange symbol
     * 
     * @return Portfolier\Entity\Quotations\AbstractPortfolioQuotations
     */
    abstract public function setExchange(string $exchange): AbstractPortfolioQuotations;

    /**
     * Get an array of quotations 
     * 
     * @return array
     */
    abstract public function getQuotations(): array;

    /**
     * Set an array of quotations
     *
     * @param array $quotations An array of quotations 
     * 
     * @return Portfolier\Entity\Quotations\AbstractPortfolioQuotations
     */
    abstract public function setQuotations(array $quotations): AbstractPortfolioQuotations;
}