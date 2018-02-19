<?php
namespace Portfolier\Collection;

use Portfolier\Source\SourceInterface;

class SourceCollection
{
    /**
     * @var array
     */
    protected $sources = [];

    /**
     * @param interable $sources An array of SourceInterface entities
     */
    public function __construct(iterable $sources)
    {
       foreach ($sources as $source) {
           $this->sources[$source->getName()] = $source;
       }
    }

    /**
     * Set or add a value to this sources array
     *
     * @param mixed $key A value key
     * @param Portfolier\Source\SourceInterface $source A Source Entity
     *
     * @return Portfolier\Collection\SourceCollection This object
     */
    public function set($key, SourceInterface $source): SourceCollection
    {
        $this->sources[$key] = $source;
    }

    /**
     * Get a source by the key
     *
     * @param mixed $key A key
     *
     * @return Portfolier\Source\SourceInterface A Source
     */
    public function get($key): SourceInterface
    {
        return $this->source[$key];
    }

    /**
     * Get all Sources
     *
     * @return array A Source array
     */
    public function getSources(): array
    {
        return $this->sources;
    }
}