<?php
namespace Portfolier\Entity\Quotations;

use Portfolier\Entity\Stock;

abstract class AbstractQuotations
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
    protected $exchange;

    /**
     * An array of quotations 
     *
     * @var array
     */
    protected $quotations;

    /**
     * Get a Stock
     *
     * @return Portfolier\Entity\Stock
     */
    abstract public function getStock(): Stock;

    /**
     * Set a Stock
     *
     * @param Portfolier\Entity\Stock $stock A Stock
     * 
     * @return Portfolier\Entity\Quotations\AbstractQuotations
     */
    abstract public function setStock(Stock $stock): AbstractQuotations;

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
     * @return Portfolier\Entity\Quotations\AbstractQuotations
     */
    abstract public function setExchange(string $exchange): AbstractQuotations;

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
     * @return Portfolier\Entity\Quotations\AbstractQuotations
     */
    abstract public function setQuotations(array $quotations): AbstractQuotations;
}