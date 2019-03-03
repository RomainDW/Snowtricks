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
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AccountFormHandler
{
    private $dispatcher;
    private $userRepository;
    private $flashBag;
    private $url_generator;

    /**
     * AccountFormHandler constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     * @param UserRepository           $userRepository
     * @param FlashBagInterface        $flashBag
     * @param UrlGeneratorInterface    $url_generator
     */
    public function __construct(EventDispatcherInterface $dispatcher, UserRepository $userRepository, FlashBagInterface $flashBag, UrlGeneratorInterface $url_generator)
    {
        $this->dispatcher = $dispatcher;
        $this->userRepository = $userRepository;
        $this->flashBag = $flashBag;
        $this->url_generator = $url_generator;
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
                $updatedUserDTO->picture->setFile(null);
                $updatedUserDTO->picture->setAlt('Photo de profil de '.$user->getUsername());
            }

            $user->updateFromDTO($updatedUserDTO);
            $this->userRepository->save($user);
            $this->flashBag->add('success', 'Votre compte a bien été modifié !');

            return new RedirectResponse($this->url_generator->generate('app_account'));
        } else {
            return false;
        }
    }
}
