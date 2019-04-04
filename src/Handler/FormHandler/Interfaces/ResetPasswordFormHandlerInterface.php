<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 4/4/19
 * Time: 10:41 AM.
 */

namespace App\Handler\FormHandler\Interfaces;

use App\Domain\Entity\Interfaces\UserInterface;
use App\Domain\Service\Interfaces\UserServiceInterface;
use App\Repository\UserRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

interface ResetPasswordFormHandlerInterface
{
    public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder, UserServiceInterface $userService);

    public function handle(FormInterface $form, UserInterface $user): bool;
}
