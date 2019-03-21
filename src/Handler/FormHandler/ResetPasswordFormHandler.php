<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 3/4/19
 * Time: 7:05 PM.
 */

namespace App\Handler\FormHandler;

use App\Domain\Entity\User;
use App\Domain\Service\UserService;
use App\Repository\UserRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPasswordFormHandler
{
    private $userRepository;
    private $passwordEncoder;
    /**
     * @var UserService
     */
    private $userService;

    /**
     * ResetPasswordFormHandler constructor.
     *
     * @param UserRepository               $userRepository
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param UserService                  $userService
     */
    public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder, UserService $userService)
    {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->userService = $userService;
    }

    /**
     * @param FormInterface $form
     * @param User          $user
     *
     * @return bool|RedirectResponse
     *
     * @throws \App\Domain\Exception\ValidationException
     */
    public function handle(FormInterface $form, User $user)
    {
        if ($form->isSubmitted() && $form->isValid() && $user->exist($form->get('email')->getData())) {
            $password = $this->passwordEncoder->encodePassword($user, $form->get('plainPassword')->getData());
            $user->newPassword($password);
            $user->eraseCredentials();
            $this->userService->save($user);

            return true;
        }

        return false;
    }
}
