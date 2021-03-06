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
use App\Domain\Service\Interfaces\UserServiceInterface;
use App\Event\UserPictureUploadEvent;
use App\Handler\FormHandler\Interfaces\RegistrationFormHandlerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationFormHandler implements RegistrationFormHandlerInterface
{
    private $passwordEncoder;
    private $dispatcher;
    private $validator;
    private $userService;

    /**
     * RegistrationFormHandler constructor.
     *
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param EventDispatcherInterface     $dispatcher
     * @param ValidatorInterface           $validator
     * @param UserServiceInterface         $userService
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, EventDispatcherInterface $dispatcher, ValidatorInterface $validator, UserServiceInterface $userService)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->dispatcher = $dispatcher;
        $this->validator = $validator;
        $this->userService = $userService;
    }

    /**
     * @param FormInterface $form
     *
     * @return User|bool
     *
     * @throws \Exception
     */
    public function handle(FormInterface $form): bool
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

            $this->userService->register($user);

            return true;
        }

        return false;
    }
}
