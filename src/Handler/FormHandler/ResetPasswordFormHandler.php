<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 3/4/19
 * Time: 7:05 PM.
 */

namespace App\Handler\FormHandler;

use App\Domain\Entity\Interfaces\UserInterface;
use App\Domain\Service\Interfaces\UserServiceInterface;
use App\Handler\FormHandler\Interfaces\ResetPasswordFormHandlerInterface;
use App\Repository\UserRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPasswordFormHandler implements ResetPasswordFormHandlerInterface
{
    private $userRepository;
    private $passwordEncoder;
    /**
     * @var UserServiceInterface
     */
    private $userService;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * ResetPasswordFormHandler constructor.
     *
     * @param UserRepository               $userRepository
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param UserServiceInterface         $userService
     */
    public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder, UserServiceInterface $userService, FlashBagInterface $flashBag)
    {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->userService = $userService;
        $this->flashBag = $flashBag;
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
            if (!$user->exist($form->get('email')->getData())) {
                $this->flashBag->add('error', 'mauvaise adresse e-mail.');

                return false;
            }

            $password = $this->passwordEncoder->encodePassword($user, $form->get('plainPassword')->getData());
            $user->newPassword($password);
            $user->eraseCredentials();
            $this->userService->save($user);

            return true;
        }

        return false;
    }
}
