<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 4/4/19
 * Time: 10:34 AM.
 */

namespace App\Handler\FormHandler\Interfaces;

use App\Domain\Service\Interfaces\UserServiceInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

interface RegistrationFormHandlerInterface
{
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, EventDispatcherInterface $dispatcher, ValidatorInterface $validator, UserServiceInterface $userService);

    public function handle(FormInterface $form): bool;
}
