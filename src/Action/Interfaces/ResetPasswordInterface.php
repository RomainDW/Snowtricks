<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/30/19
 * Time: 6:00 PM.
 */

namespace App\Action\Interfaces;

use App\Domain\Entity\User;
use App\Handler\FormHandler\Interfaces\ResetPasswordFormHandlerInterface;
use App\Handler\FormHandler\ResetPasswordFormHandler;
use App\Responder\Interfaces\TwigResponderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Security;

interface ResetPasswordInterface
{
    public function __construct(
        FormFactoryInterface $formFactory,
        FlashBagInterface $flashBag,
        Security $security,
        ResetPasswordFormHandlerInterface $formHandler
    );

    public function __invoke(User $user, Request $request, TwigResponderInterface $responder);
}
