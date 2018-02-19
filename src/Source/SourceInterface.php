<?php
namespace Portfolier\Source;

use Portfolier\Entity\Stock;
use Portfolier\Entity\Quotations\AbstractQuotations;

interface SourceInterface
{
    /**
     * Get response of a Stock quotations from a Source and fabricate a Quotations entity
     *
     * @param Portfolier\Entity\Stock $stock A Stock entity which contain all necessary information
     * 
     * @return Portfolier\Entity\Quotations\AbstractQuotations
     */
    public function getQuotations(Stock $stock): AbstractQuotations;

    /**
     * Get the name constant of the source
     *
     * @return string The name constant
     */
    public function getName(): string;
}