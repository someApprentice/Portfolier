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

class PortfolioController extends Controller
{
    public function new(Request $request): Response
    {
        $authorizer = $this->container->get(Authorizer::class);

        $portfolier = $this->container->get(Portfolier::class);

        $logged = $authorizer->getLogged();

        if (!$logged) {
            return $this->redirectToRoute('index');
        }

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

    public function portfolios(Request $request): Response
    {
        $authorizer = $this->container->get(Authorizer::class);

        $portfolier = $this->container->get(Portfolier::class);

        $logged = $authorizer->getLogged();

        if (!$logged) {
            return $this->redirectToRoute('index');
        }

        $portfolios = $portfolier->getUserPortfolios($logged);

        return $this->render('portfolios.html.twig', ['logged' => $logged]);
    }

    public function edit(Request $request, $id): Response
    {
        $authorizer = $this->container->get(Authorizer::class);

        $portfolier = $this->container->get(Portfolier::class);

        $logged = $authorizer->getLogged();

        if (!$logged) {
            return $this->redirectToRoute('index');
        }

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

    public function delete(Request $request, $id): Response
    {
        $authorizer = $this->container->get(Authorizer::class);

        $portfolier = $this->container->get(Portfolier::class);

        $logged = $authorizer->getLogged();

        if (!$logged) {
            return $this->redirectToRoute('index');
        }

        $portfolio = $portfolier->getPortfolio($id);

        if ($portfolio) {
            $portfolier->removePortfolio($portfolio);
        }

        return $this->redirectToRoute('portfolios');
    }
}