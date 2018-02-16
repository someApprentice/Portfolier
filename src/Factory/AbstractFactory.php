<?php
namespace Portfolier\Factory;

use Portfolier\Entity\SourceResult\AbstractResult;

abstract class AbstractFactory
{
    /**
     * Fabricate a Result entity from a response text of a Source
     *
     * @param string $r A text response of a Source
     *
     * @return Portfolier\Entity\AbstractResult;
     */
    abstract public function createResultFromSourceResponse(string $r): AbstractResult;
}