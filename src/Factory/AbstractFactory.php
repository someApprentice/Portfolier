<?php
namespace Portfolier\Factory;

use Portfolier\Entity\Quotations\AbstractQuotations;

abstract class AbstractFactory
{
    /**
     * Fabricate a Quotations entity from a response text of a Source
     *
     * @param string $r A text response of a Source
     *
     * @return Portfolier\Entity\Quotations\AbstractQuotations;
     */
    abstract public function createQuotations(string $r): AbstractQuotations;
}