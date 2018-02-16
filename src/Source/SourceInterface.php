<?php
namespace Portfolier\Source;

use Portfolier\Entity\Stock;
use Portfolier\Entity\SourceResult\AbstractResult;

interface SourceInterface
{
    /**
     * Get response of a Stock quotations from a Source and fabricate a Result entity
     *
     * @param Portfolier\Entity\Stock $stock A Stock entity which contain all necessary information
     * 
     * @return Portfolier\Entity\SourceResult\AbstractResult
     */
    public function getQuotations(Stock $stock);//: AbstractResult; 
}