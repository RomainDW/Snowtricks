<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 3/4/19
 * Time: 7:44 PM.
 */

namespace App\Handler\FormHandler;

use App\Domain\Entity\Picture;
use App\Domain\Entity\User;
use App\Event\UserPictureUploadEvent;
use App\Repository\UserRepository;
use Swift_Mailer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
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
     *
     * @return User|bool
     *
     * @throws \Exception
     */
    public function handle(FormInterface $form)
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $user = new User();
            $userDTO = $form->getData();

            // encode the plain password
            $userDTO->password = $this->passwordEncoder->encodePassword($user, $userDTO->password);

            /** @var Picture $picture */
            $picture = $userDTO->picture;

            if (null !== $picture) {
                $event = new UserPictureUploadEvent($picture);
                $this->dispatcher->dispatch(UserPictureUploadEvent::NAME, $event);
                $picture->setUser($user);
                $picture->setAlt('Photo de profil de '.$userDTO->username);
                $picture->setFile(null);
            }

            $user->createFromRegistration($userDTO);

            $violations = $this->validator->validate($user, null, ['registration']);

            if (0 !== count($violations)) {
                foreach ($violations as $violation) {
                    $form->get($violation->getPropertyPath())->addError(new FormError($violation->getMessage()));
                }

                return false;
            }

            return $user;
        }

        return false;
    }
}
