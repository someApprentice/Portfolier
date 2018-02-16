<?php
namespace Portfolier\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Response;

use Portfolier\Entity\User;
use Portfolier\Service\Authorizer;

class AuthorizationController extends Controller
{
    /**
     * Registration page
     *
     * @param Symfony\Component\HttpFoundation\Request $request HTTP request 
     *
     * @return Symfony\Component\HttpFoundation\Response 
     */
    public function registration(Request $request): Response
    {
        $authorizer = $this->container->get(Authorizer::class);

        if ($authorizer->isLoggedIn()) {
            return $this->redirectToRoute('index');
        }

        $user = new User();

        $registrationForm = $this->createFormBuilder($user)
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
                  ->add('registration', SubmitType::class, ['label' => 'Register'])
                  ->getForm();


        $registrationForm->handleRequest($request);

        if ($registrationForm->isSubmitted() && $registrationForm->isValid()) {
            $user = $registrationForm->getData();

            $user = $authorizer->register($user);

            if ($user) {
                return $this->redirectToRoute('index');
            }

            $registrationForm->get('email')->addError(new FormError("This User is already exist"));
        }

        return $this->render('registration.html.twig', ['registrationForm' => $registrationForm->createView()]);
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
        $authorizer = $this->container->get(Authorizer::class);

        if ($authorizer->isLoggedIn()) {
            return $this->redirectToRoute('index');
        }

        $user = new User();

        $loginForm = $this->createFormBuilder($user)
                  ->add('email', EmailType::class)
                  ->add('password', PasswordType::class)
                  ->add('login', SubmitType::class, ['label' => 'Login'])
                  ->getForm();


        $loginForm->handleRequest($request);

        if ($loginForm->isSubmitted() && $loginForm->isValid()) {
            $user = $loginForm->getData();

            $user = $authorizer->login($user);

            if ($user) {
                return $this->redirectToRoute('index');
            }

            $loginForm->get('email')->addError(new FormError("The email or password is incorrect"));
        }

        return $this->render('login.html.twig', ['loginForm' => $loginForm->createView()]);
    }

    /**
     * Logout the User
     *
     * @param Symfony\Component\HttpFoundation\Request $request HTTP request 
     *
     * @return Symfony\Component\HttpFoundation\Response 
     */
    public function logout(Request $request): Response
    {
        $authorizer = $this->container->get(Authorizer::class);

        $logged = $authorizer->getLogged();

        if ($logged) {
            $authorizer->logout();
        }

        return $this->redirectToRoute('index');
    }
}