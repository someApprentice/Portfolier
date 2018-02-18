<?php
namespace Portfolier\Entity\Quotations;

use Portfolier\Entity\Quotations\AbstractQuotations;
use Portfolier\Entity\Stock;

class YahooFinanceQuotations extends AbstractQuotations
{
    /**
     * A Stock Entity
     *
     * @var Portfolier\Entity\Stock
     */
    protected $stock;

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
     * Get a Stock
     *
     * @return Portfolier\Entity\Stock
     */
    public function getStock(): Stock
    {
        return $this->stock;
    }

    /**
     * Set a Stock
     *
     * @param Portfolier\Entity\Stock $stock A Stock
     * 
     * @return Portfolier\Entity\Quotations\AbstractQuotations
     */
    public function setStock(Stock $stock): AbstractQuotations
    {
        $this->stock = $stock;

        return $this;
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
     * @return Portfolier\Entity\Quotations\AbstractQuotations
     */
    public function setExchange(string $exchange): AbstractQuotations
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
     * @return Portfolier\Entity\Quotations\AbstractQuotations
     */
    public function setQuotations(array $quotations): AbstractQuotations
    {
        $this->quotations = $quotations;

        return $this;
    }
}