<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 4/4/19
 * Time: 9:56 AM.
 */

namespace App\Handler\FormHandler\Interfaces;

use App\Domain\Entity\Interfaces\UserInterface;
use App\Domain\Service\Interfaces\UserServiceInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

interface AccountFormHandlerInterface
{
    public function __construct(EventDispatcherInterface $dispatcher, FlashBagInterface $flashBag, ValidatorInterface $validator, UserServiceInterface $userService);
    public function handle(FormInterface $form, UserInterface $user): bool;
}
