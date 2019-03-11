<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 3/3/19
 * Time: 8:25 PM.
 */

namespace App\Handler\FormHandler;

use App\Domain\Entity\User;
use App\Event\UserPictureRemoveEvent;
use App\Event\UserPictureUploadEvent;
use App\Repository\UserRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AccountFormHandler
{
    private $dispatcher;
    private $userRepository;
    private $flashBag;
    private $url_generator;
    private $validator;

    /**
     * AccountFormHandler constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     * @param UserRepository           $userRepository
     * @param FlashBagInterface        $flashBag
     * @param UrlGeneratorInterface    $url_generator
     * @param ValidatorInterface       $validator
     */
    public function __construct(EventDispatcherInterface $dispatcher, UserRepository $userRepository, FlashBagInterface $flashBag, UrlGeneratorInterface $url_generator, ValidatorInterface $validator)
    {
        $this->dispatcher = $dispatcher;
        $this->userRepository = $userRepository;
        $this->flashBag = $flashBag;
        $this->url_generator = $url_generator;
        $this->validator = $validator;
    }

    public function handle(FormInterface $form, User $user)
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $updatedUserDTO = $form->getData();

            if (null !== $updatedUserDTO->picture && null !== $updatedUserDTO->picture->getFile()) {
                if (null !== $user->getPicture()) {
                    $removeEvent = new UserPictureRemoveEvent($user->getPicture());
                    $this->dispatcher->dispatch(UserPictureRemoveEvent::NAME, $removeEvent);
                } else {
                    $updatedUserDTO->picture->setUser($user);
                }
                $uploadEvent = new UserPictureUploadEvent($updatedUserDTO->picture);
                $this->dispatcher->dispatch(UserPictureUploadEvent::NAME, $uploadEvent);
                $updatedUserDTO->picture->setAlt('Photo de profil de '.$user->getUsername());
                $updatedUserDTO->picture->setFile(null);
            }

            $user->updateFromDTO($updatedUserDTO);

            $violations = $this->validator->validate($user, null, ['update_user']);

            if (0 !== count($violations)) {
                foreach ($violations as $violation) {
                    $this->flashBag->add('error', $violation->getMessage());
                }

                return false;
            }

            return true;
        }

        $form->getData()->picture->setFile(null);

        return false;
    }
}
