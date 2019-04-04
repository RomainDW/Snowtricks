<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 4/4/19
 * Time: 10:33 AM.
 */

namespace App\Handler\FormHandler\Interfaces;

use App\Domain\Service\Interfaces\UserServiceInterface;
use Symfony\Component\Form\FormInterface;

interface ForgotPasswordFormHandlerInterface
{
    public function __construct(UserServiceInterface $userService);

    public function handle(FormInterface $form): bool;
}
