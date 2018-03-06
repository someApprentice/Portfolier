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
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
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

        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('index');
        }

        $user = new User();

        $registrationForm = $this->createFormBuilder($user)
                  ->add('email', EmailType::class)
                  ->add('username', TextType::class)
                  ->add('plainPassword', RepeatedType::class, [
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

            if (!$authorizer->isExist($user)) {
                $user = $authorizer->register($user);

                return $this->redirectToRoute('login');
            }

            $registrationForm->get('email')->addError(new FormError("This User is already exist"));
        }

        return $this->render('registration.html.twig', ['registrationForm' => $registrationForm->createView()]);
    }

    /**
     * Login page
     *
     * @param Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param Symfony\Component\Security\Http\Authentication\AuthenticationUtils
     *
     * @return Symfony\Component\HttpFoundation\Response 
     */
    public function login(Request $request,  AuthenticationUtils $authUtils): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('index');
        }

        $user = new User();

        $error = $authUtils->getLastAuthenticationError();

        $lastUsername = $authUtils->getLastUsername();

        $loginForm = $this->createFormBuilder($user)
                  ->add('_email', EmailType::class)
                  ->add('_password', PasswordType::class)
                  ->add('login', SubmitType::class, ['label' => 'Login'])
                  ->getForm();

        return $this->render('login.html.twig', ['loginForm' => $loginForm->createView(), 'lastUsername' => $lastUsername, 'error' => $error]);
    }

    /**
     * Logout the User
     *
     * @param Symfony\Component\HttpFoundation\Request $request HTTP request 
     *
     * @throws \Excpetion If somehow this reached
     */
    public function logout(Request $request): Response
    {
        throw new \Exception('This should never be reached!');
    }
}