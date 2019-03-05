<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Handler\FormHandler\RegistrationFormHandler;
use App\Service\AccountVerification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationController extends AbstractController
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @Route("/register", name="app_register")
     *
     * @param Request                 $request
     * @param RegistrationFormHandler $formHandler
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function register(Request $request, RegistrationFormHandler $formHandler): Response
    {
        if ($this->isGranted('ROLE_USER')) {
            $this->addFlash('error', 'Vous êtes déjà connecté(e)');

            return $this->redirectToRoute('homepage');
        }
        $form = $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);

        if (($response = $formHandler->handle($form)) instanceof RedirectResponse) {
            return $response;
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/register/{id}", name="app_mail_sent")
     *
     * @param User $user
     *
     * @return Response
     */
    public function mailSent(User $user)
    {
        $hasAccess = $user->hasRole('ROLE_USER_NOT_VERIFIED');

        if (!$hasAccess) {
            $this->addFlash('error', 'Votre compte est déjà vérifié.');

            return $this->redirectToRoute('homepage');
        }

        $userEmail = $user->getEmail();

        return $this->render('registration/registration-pre-validation.html.twig', [
            'userEmail' => $userEmail,
        ]);
    }

    /**
     * @Route("/verification/{vkey}", name="app_verification", methods={"GET"})
     *
     * @param User                $user
     * @param AccountVerification $accountVerification
     *
     * @return Response
     */
    public function registerVerification(User $user, AccountVerification $accountVerification)
    {
        $verification = $accountVerification->verification($user);

        if (false === $verification) {
            $this->addFlash('error', 'Votre compte est déjà vérifié.');

            return $this->redirectToRoute('homepage');
        } else {
            // Connexion à mettre dans service
            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->get('security.token_storage')->setToken($token);
            $this->get('session')->set('_security_secured_area', serialize($token));

            return $this->render('registration/registration-confirmed.html.twig');
        }
    }
}
