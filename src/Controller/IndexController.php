<?php
namespace Portfolier\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Response;

use Portfolier\Entity\User;
use Portfolier\Service\Authorizer;

class IndexController extends Controller
{
    /**
    * Index page
    *
    * @param Symfony\Component\HttpFoundation\Request $request HTTP request 
    *
    * @return Symfony\Component\HttpFoundation\Response 
    */
    public function index(Request $request): Response
    {
        $authorizer = $this->container->get(Authorizer::class);

        $logged = $authorizer->getLogged();

        if (!$logged) {
            $user = new User();

            $loginForm = $this->createFormBuilder($user)
                        ->setAction($this->generateUrl('login'))
                        ->add('email', EmailType::class)
                        ->add('password', PasswordType::class)
                        ->add('login', SubmitType::class, ['label' => 'Login'])
                        ->getForm();

            return $this->render('login.html.twig', ['loginForm' => $loginForm->createView()]);
        }

        return $this->render('index.html.twig', ['logged' => $logged]);
    }
}