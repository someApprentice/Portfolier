<?php

namespace Portfolier\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Portfolier\Entity\User;

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
