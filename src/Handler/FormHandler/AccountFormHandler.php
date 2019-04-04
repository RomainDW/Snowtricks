<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 3/3/19
 * Time: 8:25 PM.
 */

namespace App\Handler\FormHandler;

use App\Domain\Entity\Interfaces\UserInterface;
use App\Domain\Service\Interfaces\UserServiceInterface;
use App\Event\UserPictureRemoveEvent;
use App\Event\UserPictureUploadEvent;
use App\Handler\FormHandler\Interfaces\AccountFormHandlerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AccountFormHandler implements AccountFormHandlerInterface
{
    private $dispatcher;
    private $flashBag;
    private $validator;
    private $userService;

    /**
     * AccountFormHandler constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     * @param FlashBagInterface        $flashBag
     * @param ValidatorInterface       $validator
     * @param UserServiceInterface     $userService
     */
    public function __construct(EventDispatcherInterface $dispatcher, FlashBagInterface $flashBag, ValidatorInterface $validator, UserServiceInterface $userService)
    {
        $this->dispatcher = $dispatcher;
        $this->flashBag = $flashBag;
        $this->validator = $validator;
        $this->userService = $userService;
    }

    /**
     * @param FormInterface $form
     * @param UserInterface $user
     *
     * @return bool
     */
    public function handle(FormInterface $form, UserInterface $user): bool
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

            $this->userService->update($user);

            return true;
        }

        $form->getData()->picture->setFile(null);

        return false;
    }
}
