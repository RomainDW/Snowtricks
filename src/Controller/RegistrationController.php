<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Entity\User;
use App\Event\UserPictureUploadEvent;
use App\Form\RegistrationFormType;
use App\Service\AccountVerification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
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
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param \Swift_Mailer                $mailer
     * @param EventDispatcherInterface     $dispatcher
     *
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer, EventDispatcherInterface $dispatcher): Response
    {
        if ($this->isGranted('ROLE_USER')) {
            $this->addFlash('error', 'Vous êtes déjà connecté(e)');

            return $this->redirectToRoute('homepage');
        }
        $form = $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = new User();
            $userDTO = $form->getData();
            // encode the plain password
            $userDTO->password = $passwordEncoder->encodePassword($user, $userDTO->password);
            $pictureDTO = $userDTO->picture;

            $user->createFromRegistration($userDTO);

            if (null !== $pictureDTO) {
                $event = new UserPictureUploadEvent($pictureDTO);
                $dispatcher->dispatch(UserPictureUploadEvent::NAME, $event);
                $pictureDTO->user = $user;

                $userPicture = new Picture();
                $userPicture->createFromRegistration($pictureDTO);
                $user->setPicture($userPicture);
            }

            $violations = $this->validator->validate($user);

            if (0 !== count($violations)) {
                foreach ($violations as $violation) {
                    $this->addFlash('error', $violation->getMessage());
                }
            } else {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                $message = (new \Swift_Message('Confirmation de création de compte'))
                    ->setFrom('noreply@snowtricks.com')
                    ->setTo('romain.ollier34@gmail.com')
                    ->setBody(
                        $this->renderView(
                            'emails/registration.html.twig',
                            ['name' => $form->get('username')->getData(), 'vkey' => $userDTO->vkey]
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

                return $this->redirectToRoute('app_mail_sent', ['id' => $user->getId()]);
            }
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
