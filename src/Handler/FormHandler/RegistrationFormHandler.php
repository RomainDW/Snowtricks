<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 3/4/19
 * Time: 7:44 PM.
 */

namespace App\Handler\FormHandler;

use App\Entity\User;
use App\Event\UserPictureUploadEvent;
use App\Repository\UserRepository;
use Swift_Mailer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationFormHandler
{
    private $userRepository;
    private $passwordEncoder;
    private $dispatcher;
    private $validator;
    private $flashBag;
    private $url_generator;
    private $mailer;
    private $templating;

    /**
     * RegistrationFormHandler constructor.
     *
     * @param UserRepository               $userRepository
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param EventDispatcherInterface     $dispatcher
     * @param ValidatorInterface           $validator
     * @param FlashBagInterface            $flashBag
     * @param UrlGeneratorInterface        $url_generator
     * @param Swift_Mailer                 $mailer
     * @param \Twig_Environment            $templating
     */
    public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder, EventDispatcherInterface $dispatcher, ValidatorInterface $validator, FlashBagInterface $flashBag, UrlGeneratorInterface $url_generator, Swift_Mailer $mailer, \Twig_Environment $templating)
    {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->dispatcher = $dispatcher;
        $this->validator = $validator;
        $this->flashBag = $flashBag;
        $this->url_generator = $url_generator;
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    /**
     * @param FormInterface $form
     * @return bool|RedirectResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function handle(FormInterface $form)
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $user = new User();
            $userDTO = $form->getData();

            // encode the plain password
            $userDTO->password = $this->passwordEncoder->encodePassword($user, $userDTO->password);

            if (null !== $userDTO->picture) {
                $event = new UserPictureUploadEvent($userDTO->picture);
                $this->dispatcher->dispatch(UserPictureUploadEvent::NAME, $event);
                $userDTO->picture->setUser($user);
                $userDTO->picture->setAlt('Photo de profil de '.$userDTO->username);
            }

            $user->createFromRegistration($userDTO);

            $violations = $this->validator->validate($user);

            if (0 !== count($violations)) {
                foreach ($violations as $violation) {
                    $this->flashBag->add('error', $violation->getMessage());
                }

                return false;
            } else {
                $this->userRepository->save($user);

                $message = (new \Swift_Message('Confirmation de création de compte'))
                    ->setFrom('noreply@snowtricks.com')
                    ->setTo('romain.ollier34@gmail.com')
                    ->setBody(
                        $this->templating->render(
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

                $this->mailer->send($message);

                return new RedirectResponse($this->url_generator->generate('app_mail_sent', ['id' => $user->getId()]));
            }
        } else {
            return false;
        }
    }
}
