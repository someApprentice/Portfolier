<?php

namespace Portfolier\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Portfolier\Entity\Portfolio;

/**
 * @ORM\Entity(repositoryClass="Portfolier\Repository\StockRepository")
 * @ORM\Table(name="`stock`")
 */
class Stock
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Portfolio", inversedBy="stocks")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     *
     * @var Portfolier\Entity\Portfolio A Portfolio to which the Stock belongs
     */
    private $portfolio;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\Regex("/^[\w\d]+$/")
     *
     * @var string A Stock symbol
     */
    private $symbol = '';

    /**
     * @ORM\Column(type="datetimetz")
     *
     * @var \DateTime A date and time when the Stock was added
     */
    private $date;

    /**
     * Get a Stock ID
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set a Stock ID
     *
     * @param int $id An ID
     *
     * @return Portfolier\Entity\Stock
     */
    public function setId(int $id): Stock
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get a Portfolio to which the Stock belongs
     *
     * @return Portfolier\Entity\Portfolio
     */
    public function getPortfolio(): Portfolio
    {
        return $this->portfolio;
    }

    /**
     * Set a Portfolio to which the Stock belongs
     *
     * @param Portfolier\Entity\Portfolio $portfolio An Portfolio entity
     *
     * @return Portfolier\Entity\Stock
     */
    public function setPortfolio(Portfolio $portfolio): Stock
    {
        $this->portfolio = $portfolio;

        return $this;
    }

    /**
     * Get a Stock symbol
     *
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->symbol;
    }

    /**
     * Set a Stock symbol and turn it to upper case
     *
     * @param string $symbol A symbol
     *
     * @return Portfolier\Entity\Stock
     */
    public function setSymbol(string $symbol): Stock
    {
        $this->symbol = strtoupper($symbol);

        return $this;
    }

    /**
     * Get a Stock date
     *
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * Set date to current time
     *
     * @return Portfolier\Entity\Stock
     */
    public function setDate(): Stock
    {
        $this->date = new \Datetime("now");

        return $this;
    }
}
