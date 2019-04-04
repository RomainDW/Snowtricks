<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/30/19
 * Time: 5:58 PM.
 */

namespace App\Action\Interfaces;

use App\Domain\Service\Interfaces\UserServiceInterface;
use App\Handler\FormHandler\Interfaces\RegistrationFormHandlerInterface;
use App\Responder\Interfaces\TwigResponderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Security;

interface RegistrationActionInterface
{
    public function __construct(
        FormFactoryInterface $formFactory,
        Security $security,
        FlashBagInterface $flashBag,
        UserServiceInterface $userService,
        RegistrationFormHandlerInterface $formHandler
    );

    public function __invoke(Request $request, TwigResponderInterface $responder);
}
