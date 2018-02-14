<?php
namespace Portfolier\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;

use Portfolier\Entity\User;
use Portfolier\Service\Authorizer;

class PortfolierController extends Controller
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
        $em = $this->getDoctrine()->getManager();

        $authorizer = new Authorizer($em);

        $user = new User();

        $form = $this->createFormBuilder($user)
                  ->setAction($this->generateUrl('registration'))
                  ->add('email', EmailType::class)
                  ->add('name', TextType::class)
                  ->add('password', RepeatedType::class, [
                      'type' => PasswordType::class,
                      'invalid_message' => 'The password fields must match.',
                      'required' => true,
                      'first_name' => 'password',
                      'first_options'  => ['label' => 'Password'],
                      'second_name' => 'repeat-password',
                      'second_options' => ['label' => 'Repeat Password']
                  ])
                  ->add('save', SubmitType::class, ['label' => 'Register'])
                  ->getForm();

        return $this->render('index.html.twig', ['form' => $form->createView()]);
    }


    /**
     * Registration page
     *
     * @param Symfony\Component\HttpFoundation\Request $request HTTP request 
     *
     * @return Symfony\Component\HttpFoundation\Response 
     */
    public function registration(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        $authorizer = new Authorizer($em);

        if ($authorizer->isLoggedIn()) {
            return $this->redirectToRoute('index');
        }

        $user = new User();

        $form = $this->createFormBuilder($user)
                  ->add('email', EmailType::class)
                  ->add('name', TextType::class)
                  ->add('password', PasswordType::class)
                  ->add('save', SubmitType::class, ['label' => 'Register'])
                  ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $user = $authorizer->register($user);

            if ($user) {
                return $this->redirectToRoute('index');
            }

            return $this->render('login.html.twig', ['errors' => []]);
        }

        return $this->render('registration.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Login page
     *
     * @param Symfony\Component\HttpFoundation\Request $request HTTP request 
     *
     * @return Symfony\Component\HttpFoundation\Response 
     */
    public function login(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        $authorizer = new Authorizer($em);

        if ($authorizer->isLoggedIn()) {
            return $this->redirectToRoute('index');
        }

        $user = new User();

        $form = $this->createFormBuilder($user)
                  ->add('email', EmailType::class)
                  ->add('password', PasswordType::class)
                  ->add('save', SubmitType::class, ['label' => 'Login'])
                  ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $user = $authorizer->login($user);

            if ($user) {
                return $this->redirectToRoute('index');
            }

            return $this->render('login.html.twig', ['errors' => []]);
        }

        return $this->render('login.html.twig', ['form' => $form->createView()]);
    }
}