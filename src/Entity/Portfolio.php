<?php

namespace Portfolier\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Portfolier\Entity\User;
use Portfolier\Entity\Stock;
use Portfolier\Entity\Quotations\PortfolioQuotations;
use Portfolier\Source\SourceInterface;

/**
 * @ORM\Entity(repositoryClass="Portfolier\Repository\PortfolioRepository")
 * @ORM\Table(name="`portfolio`")
 */
class Portfolio
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\Regex("/^\w+$/")
     * @Assert\Length(min = 3, max = 255)
     *
     * @var string A Portfolio name
     */
    private $name = '';

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="portfolios")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     *
     * @var Portfolier\Entity\User An User to who the Portfolio belongs 
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="Stock", mappedBy="portfolio", cascade={"all"})
     */
    private $stocks;

    /**
     * Calculate a Portfolio quotations of all contained Stocks in it
     *
     * @param Portfolier\Source\SourceInterface $source A Source Entity
     *
     * @return array An array of a PortfolioQuotations entities sorted by exchange of Stocks
     */
    public function getQuotations(SourceInterface $source): array
    {
        $quotations = [];

        $stocks = $this->stocks;

        foreach ($stocks as $stock) {
            $q = $source->getQuotations($stock);

            foreach ($q->getQuotations() as $key => $quotation) {
                if (array_key_exists($q->getExchange(), $quotations)) {
                    if (isset(
                        $quotations[$q->getExchange()]->getQuotations()[$key]['date'],
                        $quotations[$q->getExchange()]->getQuotations()[$key]['close'],
                        $quotations[$q->getExchange()]->getQuotations()[$key]['high'],
                        $quotations[$q->getExchange()]->getQuotations()[$key]['low'],
                        $quotations[$q->getExchange()]->getQuotations()[$key]['open'],
                        $quotations[$q->getExchange()]->getQuotations()[$key]['value']
                        )
                    ) {
                        $close = $quotations[$q->getExchange()]->getQuotations()[$key]['close'];
                        $high = $quotations[$q->getExchange()]->getQuotations()[$key]['high'];
                        $low = $quotations[$q->getExchange()]->getQuotations()[$key]['low'];
                        $open = $quotations[$q->getExchange()]->getQuotations()[$key]['open'];
                        $value = $quotations[$q->getExchange()]->getQuotations()[$key]['value'];

                        $date = $quotation['date'];
                        $close += $quotation['close'];
                        $high += $quotation['high'];
                        $low += $quotation['low'];
                        $open += $quotation['open'];
                        $value += $quotation['value'];

                        $quotations[$q->getExchange()]->setQuotation($key, [
                            'date' => $date,
                            'close' => $close,
                            'high' => $high,
                            'low' => $low,
                            'open' => $open,
                            'value' => $value
                        ]);
                    } else {
                        $quotations[$q->getExchange()]->setQuotation($key, $quotation);
                    }
                } else {
                    $portfolioQuotations = new PortfolioQuotations();
                    $portfolioQuotations->setExchange($q->getExchange());
                    $portfolioQuotations->setQuotation($key, $quotation);
                    
                    $quotations[$q->getExchange()] = $portfolioQuotations;
                }
            }
        }

        return $quotations;
    }

    /**
     * Get a Portfolio ID
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set a Portfolio ID
     *
     * @param int $id An ID
     *
     * @return Portfolier\Entity\Portfolio
     */
    public function setId(int $id): Portfolio
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get A Portfolio name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set a Portfolio name
     *
     * @param string $name A Portfolio name
     *
     * @return Portfolier\Entity\Portfolio
     */
    public function setName(string $name): Portfolio
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get an User to who the Portfolio belongs
     *
     * @return Portfolier\Entity\User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * Set an User to who the Portfolio belongs
     *
     * @param Portfolier\Entity\User $user An User entity
     *
     * @return Portfolier\Entity\Portfolio
     */
    public function setUser(User $user): Portfolio
    {
        $this->user = $user;

        return $this;
    }

    public function getStocks()
    {
        return $this->stocks;
    }

    public function setStocks($stocks): Portfolio
    {
        $this->stocks = $stocks;

        return $this;
    }
}
