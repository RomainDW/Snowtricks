<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 3/3/19
 * Time: 8:25 PM.
 */

namespace App\Handler\FormHandler;

use App\Entity\User;
use App\Event\UserPictureRemoveEvent;
use App\Event\UserPictureUploadEvent;
use App\Repository\UserRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
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

            $pictureViolations = $this->validator->validate($updatedUserDTO->picture, null, ['update_account']);

            if (0 !== count($pictureViolations)) {
                foreach ($pictureViolations as $violation) {
                    $form->get('picture')->addError(new FormError($violation->getMessage()));
                }

                $updatedUserDTO->picture->setFile(null);

                return false;
            }

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

            $violations = $this->validator->validate($updatedUserDTO, null, ['update_account']);

            if (0 !== count($violations)) {
                foreach ($violations as $violation) {
                    $this->flashBag->add('error', $violation->getMessage());
                }

                return false;
            }

            $user->updateFromDTO($updatedUserDTO);
            $this->userRepository->save($user);
            $this->flashBag->add('success', 'Votre compte a bien été modifié !');

            return new RedirectResponse($this->url_generator->generate('app_account'));

        }

        return false;
    }
}
