<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/30/19
 * Time: 5:59 PM.
 */

namespace App\Action\Interfaces;

use App\Domain\Entity\User;
use App\Domain\Service\Interfaces\UserServiceInterface;
use App\Responder\Interfaces\TwigResponderInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

interface RegistrationVerificationInterface
{
    public function __construct(
        FlashBagInterface $flashBag,
        TokenStorageInterface $tokenStorage,
        SessionInterface $session,
        UserServiceInterface $userService
    );

    public function __invoke(User $user, TwigResponderInterface $responder);
}
