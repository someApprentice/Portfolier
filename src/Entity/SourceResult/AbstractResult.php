<?php
namespace Portfolier\Entity\SourceResult;

use Portfolier\Entity\Stock;

abstract class AbstractResult
{
    /**
     * A Stock Entity
     *
     * @var Portfolier\Entity\Stock
     */
    protected $stock;

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
     * @return Portfolier\Entity\SourceResult\AbstractResult
     */
    abstract public function setStock(Stock $stock): AbstractResult;

    /**
     * Get an array of quotations 
     * 
     * @return array
     */
    abstract public function getQuotations(): array;

    /**
     * Set an array of quotations
     *
     * @param array $qoutation An array of quotations 
     * 
     * @return Portfolier\Entity\SourceResult\AbstractResult
     */
    abstract public function setQuotations(array $qoutation): AbstractResult;
}