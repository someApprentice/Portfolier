<?php
namespace Portfolier\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;

use Portfolier\Service\Authorizer;
use Portfolier\Service\Portfolier;
use Portfolier\Entity\Portfolio;
use Portfolier\Entity\Stock;

class StockController extends Controller
{
    public function add(Request $request, $portfolio_id): Response
    {
        $authorizer = $this->container->get(Authorizer::class);

        $portfolier = $this->container->get(Portfolier::class);

        $logged = $authorizer->getLogged();

        if (!$logged) {
            return $this->redirectToRoute('index');
        }

        $portfolio = $portfolier->getPortfolio($portfolio_id);

        if (!$portfolio) {
            return $this->redirectToRoute('portfolios');
        }

        $stock = new Stock();

        $addStockForm = $this->createFormBuilder($stock)
                    ->add('symbol', TextType::class)
                    ->add('add', SubmitType::class, ['label' => "Add"])
                    ->getForm();

        $addStockForm->handleRequest($request);

        if ($addStockForm->isSubmitted() and $addStockForm->isValid()) {
            $stock = $addStockForm->getData();
            $stock->setPortfolio($portfolio);
            $stock->setDate();

            $stock = $portfolier->addStock($stock);

            if ($stock) {
                return $this->redirectToRoute('edit_portfolio', ['id' => $portfolio_id]);
            }
        }

        return $this->render('add_stock.html.twig', ['logged' => $logged, 'addStockForm' => $addStockForm->createView()]);
    }

    public function edit(Request $request, $portfolio_id, $stock_id): Response
    {
        $authorizer = $this->container->get(Authorizer::class);

        $portfolier = $this->container->get(Portfolier::class);

        $logged = $authorizer->getLogged();

        if (!$logged) {
            return $this->redirectToRoute('index');
        }

        $portfolio = $portfolier->getPortfolio($portfolio_id);

        if (!$portfolio) {
            return $this->redirectToRoute('portfolios');
        }

        $stock = $portfolier->getStock($stock_id);

        $editStockForm = $this->createFormBuilder($stock)
                    ->add('symbol', TextType::class)
                    ->add('set', SubmitType::class, ['label' => "Set"])
                    ->getForm();

        $editStockForm->handleRequest($request);

        if ($editStockForm->isSubmitted() and $editStockForm->isValid()) {
            $stock = $editStockForm->getData();
            $stock->setId($stock_id);
            $stock->setPortfolio($portfolio);
            $stock->setDate();

            $stock = $portfolier->setStock($stock);

            if ($stock) {
                return $this->redirectToRoute('edit_portfolio', ['id' => $portfolio_id]);
            }
        }

        return $this->render('add_stock.html.twig', ['logged' => $logged, 'addStockForm' => $editStockForm->createView()]);
    }

    public function delete(Request $request, $portfolio_id, $stock_id): Response
    {
        $authorizer = $this->container->get(Authorizer::class);

        $portfolier = $this->container->get(Portfolier::class);

        $logged = $authorizer->getLogged();

        if (!$logged) {
            return $this->redirectToRoute('index');
        }

        $portfolio = $portfolier->getPortfolio($portfolio_id);

        if ($portfolio) {
            $stock = $portfolier->getStock($stock_id);

            if ($stock) {
                $portfolier->removeStock($stock);
            }
        }
        
        return $this->redirectToRoute('edit_portfolio', ['id' => $portfolio_id]);
    }
}