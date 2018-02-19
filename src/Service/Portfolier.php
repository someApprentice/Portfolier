<?php
namespace Portfolier\Service;

use Doctrine\ORM\EntityManagerInterface;

use Portfolier\Entity\User;
use Portfolier\Entity\Portfolio;
use Portfolier\Entity\Stock;

class Portfolier
{
    /**
     * A Doctrine Entity Manager
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Get a Portfolio by ID
     *
     * @return Portfolier\Entity\Portfolio | null The Portfolio entity or NULL if the Portfolio entity can not be found
     */
    public function getPortfolio(int $id)
    {
        $portfolio = $this->em->getRepository(Portfolio::class)->find($id);

        return $portfolio;
    }

    /**
     * Get all User Portfolis 
     *
     * @param Portfolier\Entity\User $user An User
     *
     * @return array The array of the Portfolio entities
     *
     * @throws \Exception If the User was not found in a database
     */
    public function getUserPortfolios(User $user)
    {
        $u = $this->em->getRepository(User::class)->find($user->getId());

        if (!$u) {
            throw new \Exception("The User can not be found");
        }

        $portfolios = $this->em->getRepository(Portfolio::class)->findBy(['user' => $user->getId()]);

        return $portfolios;
    }

    /**
     * Create the Portfolio in a database
     *
     * @param Portfolier\Entity\Portfolio $portfolio A Portfolio
     *
     * @return array The Portfolio entity
     *
     * @throws \Exception If the User to who the Portfolio will be belongs was not found in a database
     */
    public function createPortfolio(Portfolio $portfolio): Portfolio
    {
        $u = $this->em->getRepository(User::class)->find($portfolio->getUser()->getId());

        if (!$u) {
            throw new \Exception("The User can not be found");
        }

        $this->em->persist($portfolio);

        $this->em->flush();

        return $portfolio;
    }

    /**
     * Set the Portfolio in a database
     *
     * @param Portfolier\Entity\Portfolio $portfolio A Portfolio
     *
     * @return array The Portfolio entity
     *
     * @throws \Exception If the User to who the Portfolio will be belongs was not found in a database
     */
    public function setPortfolio(Portfolio $portfolio): Portfolio
    {
        $u = $this->em->getRepository(User::class)->find($portfolio->getUser()->getId());

        if (!$u) {
            throw new \Exception("The User can not be found");
        }

        $this->em->persist($portfolio);

        $this->em->flush();

        return $portfolio;
    }

    /**
     * Remove the Portfolio from a database
     *
     * @param Portfolier\Entity\Portfolio $portfolio A Portfolio
     *
     * @return void
     *
     */
    public function removePortfolio(Portfolio $portfolio)
    {
        $this->em->remove($portfolio);

        $this->em->flush();
    }

    /**
     * Get a Stock by ID
     *
     * @return Portfolier\Entity\Stock | null The Stock entity or NULL if the Stock entity can not be found
     */
    public function getStock(int $id)
    {
        $stock = $this->em->getRepository(Stock::class)->find($id);

        return $stock;
    }

    /**
     * Get all Stocks belongs to a the Portfolio
     *
     * @param Portfolier\Entity\Portfolio $portfolio An Portfolio
     *
     * @return array The array of the Stock entities
     *
     * @throws \Exception If the Portfolio was not found in a database
     */
    public function getPortfolioStocks(Portfolio $portfolio)
    {
        $p = $this->em->getRepository(Portfolio::class)->find($portfolio->getId());

        if (!$p) {
            throw new \Exception("The Portfolio can not be found");
        }

        $stocks = $this->em->getRepository(Stock::class)->findBy(['portfolio' => $portfolio->getId()]);

        return $stocks;
    }

    /**
     * Add The Stock to a database
     *
     * @param Portfolier\Entity\Stock $stock A Stock
     *
     * @return Portfolier\Entity\Stock
     *
     * @throws \Exception If the Portfolios to which Stock belongs was not found in a database 
     */
    public function addStock(Stock $stock): Stock
    {
        $p = $this->em->getRepository(Portfolio::class)->find($stock->getPortfolio()->getId());

        if (!$p) {
            throw new \Exception("The Portfolio can not be found");
        }

        $stock->setPortfolio($p);

        $this->em->persist($stock);

        $this->em->flush();

        return $stock;
    }

    /**
     * Set The Stock to a database
     *
     * @param Portfolier\Entity\Stock $stock A Stock
     *
     * @return Portfolier\Entity\Stock
     *
     * @throws \Exception If the Portfolios to which Stock belongs was not found in a database 
     */
    public function setStock(Stock $stock): Stock
    {
        $p = $this->em->getRepository(Portfolio::class)->find($stock->getPortfolio()->getId());

        if (!$p) {
            throw new \Exception("The Portfolio can not be found");
        }

        $stock->setPortfolio($p);

        $this->em->persist($stock);

        $this->em->flush();

        return $stock;
    }

    /**
     * Remove the Stock from a database
     * 
     * @param Portfolier\Entity\Stock $stock A stock
     *
     * @return void
     */
    public function removeStock(Stock $stock)
    {
        $this->em->remove($stock);

        $this->em->flush();
    }

}