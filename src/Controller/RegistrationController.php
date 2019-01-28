<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\LoginFormAuthenticator;
use App\Service\AccountVerification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     *
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param GuardAuthenticatorHandler    $guardHandler
     * @param LoginFormAuthenticator       $authenticator
     * @param \Swift_Mailer                $mailer
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator, \Swift_Mailer $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vkey = md5(random_bytes(10));
            // encode the plain password
            $user->setPassword($passwordEncoder->encodePassword($user, $form->get('plainPassword')->getData()))
                ->setVkey($vkey)
                ->setRoles(['ROLE_USER_NOT_VERIFIED']);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $message = (new \Swift_Message('Confirmation de création de compte'))
                ->setFrom('noreply@snowtricks.com')
                ->setTo('romain.ollier34@gmail.com')
                ->setBody(
                    $this->renderView(
                        'emails/registration.html.twig',
                        ['name' => $form->get('email'), 'vkey' => $vkey]
                    ),
                    'text/html'
                )
                /*
                 * plaintext version
                ->addPart(
                    $this->renderView(
                        'emails/registration.txt.twig',
                        ['name' => $name]
                    ),
                    'text/plain'
                )
                */
            ;

            $mailer->send($message);

            return $this->render('registration/registration-pre-validation.html.twig', [
                'userEmail' => $user->getEmail(),
            ]);
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
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

        // Connexion à mettre dans service
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->get('security.token_storage')->setToken($token);
        $this->get('session')->set('_security_secured_area', serialize($token));

        return $this->render('registration/registration-confirmed.html.twig', [
            'verification' => $verification,
        ]);
    }
}
