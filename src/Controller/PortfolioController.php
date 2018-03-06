<?php
namespace Portfolier\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;

use Portfolier\Service\Authorizer;
use Portfolier\Service\Portfolier;
use Portfolier\Collection\SourceCollection;
use Portfolier\Entity\Portfolio;

class PortfolioController extends Controller
{
    /**
     * New Portfolio creation page
     *
     * @param Symfony\Component\HttpFoundation\Request $request HTTP request 
     *
     * @return Symfony\Component\HttpFoundation\Response 
     */
    public function new(Request $request): Response
    {
        $portfolier = $this->container->get(Portfolier::class);

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $logged = $this->getUser();

        $portfolio = new Portfolio();

        $newPortfolioForm = $this->createFormBuilder($portfolio)
                        ->add('name', TextType::class)
                        ->add('add', SubmitType::class, ['label' => 'Add'])
                        ->getForm();

        $newPortfolioForm->handleRequest($request);

        if ($newPortfolioForm->isSubmitted() && $newPortfolioForm->isValid()) {
            $portfolio = $newPortfolioForm->getData();
            $portfolio->setUser($logged);

            $portfolio = $portfolier->createPortfolio($portfolio);

            if ($portfolio) {
                return $this->redirectToRoute('portfolios');
            }
        }

        return $this->render('new_portfolio.html.twig', ['logged' => $logged, 'newPortfolioForm' => $newPortfolioForm->createView()]);
    }

    /**
     * Page with all user Portfolios
     *
     * @param Symfony\Component\HttpFoundation\Request $request HTTP request 
     *
     * @return Symfony\Component\HttpFoundation\Response 
     */
    public function portfolios(Request $request): Response
    {
        $portfolier = $this->container->get(Portfolier::class);

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $logged = $this->getUser();

        return $this->render('portfolios.html.twig', ['logged' => $logged]);
    }

    /**
     * Page of the exact Portfolio
     *
     * @param Symfony\Component\HttpFoundation\Request $request HTTP request 
     * @param int $id An ID of the Portfolio
     *
     * @return Symfony\Component\HttpFoundation\Response 
     */
    public function portfolio(Request $request, $id): Response
    {
        $portfolier = $this->container->get(Portfolier::class);

        $sources = $this->container->get(\Portfolier\Collection\SourceCollection::class);

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $logged = $this->getUser();

        $portfolio = $portfolier->getPortfolio($id);

        if (!$portfolio) {
            return $this->redirectToRoute('portfolios');
        }

        $quotations = [];

        foreach ($sources->getSources() as $key => $source) {
            $quotations[$key] = $portfolio->getQuotations($source);
        }

        return $this->render('portfolio.html.twig', ['logged' => $logged, 'portfolio' => $portfolio, 'quotations' => $quotations]);
    }

    /**
     * Portfolio editing page
     *
     * @param Symfony\Component\HttpFoundation\Request $request HTTP request 
     * @param int $id An ID of the Portfolio
     *
     * @return Symfony\Component\HttpFoundation\Response 
     */
    public function edit(Request $request, $id): Response
    {
        $portfolier = $this->container->get(Portfolier::class);

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $logged = $this->getUser();

        $portfolio = $portfolier->getPortfolio($id);

        if (!$portfolio) {
            return $this->redirectToRoute('portfolios');
        }

        $editPortfolioForm = $this->createFormBuilder($portfolio)
                        ->add('name', TextType::class)
                        ->add('set', SubmitType::class, ['label' => 'Set'])
                        ->getForm();

        $editPortfolioForm->handleRequest($request);

        if ($editPortfolioForm->isSubmitted() && $editPortfolioForm->isValid()) {
            $portfolio = $editPortfolioForm->getData();
            $portfolio->setId($id);

            $portfolio = $portfolier->setPortfolio($portfolio);

            if ($portfolio) {
                return $this->redirectToRoute('edit_portfolio', ['id' => $id]);
            }
        }

        return $this->render('edit_portfolio.html.twig', ['logged' => $logged, 'portfolio' => $portfolio, 'editPortfolioForm' => $editPortfolioForm->createView()]);
    }

    /**
     * Deletion Portfolio contoller
     *
     * @param Symfony\Component\HttpFoundation\Request $request HTTP request 
     * @param int $id An ID of the Portfolio
     *
     * @return Symfony\Component\HttpFoundation\Response 
     */
    public function delete(Request $request, $id): Response
    {
        $portfolier = $this->container->get(Portfolier::class);

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $logged = $this->getUser();

        $portfolio = $portfolier->getPortfolio($id);

        if ($portfolio) {
            $portfolier->removePortfolio($portfolio);
        }

        return $this->redirectToRoute('portfolios');
    }
}