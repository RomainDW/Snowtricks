<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/7/19
 * Time: 8:14 PM.
 */

namespace App\Action;

use App\Action\Interfaces\RegistrationVerificationInterface;
use App\Domain\Entity\User;
use App\Domain\Service\Interfaces\UserServiceInterface;
use App\Responder\Interfaces\TwigResponderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class RegistrationVerificationAction implements RegistrationVerificationInterface
{
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var UserServiceInterface
     */
    private $userService;

    /**
     * RegistrationVerificationAction constructor.
     *
     * @param FlashBagInterface     $flashBag
     * @param TokenStorageInterface $tokenStorage
     * @param SessionInterface      $session
     * @param UserServiceInterface  $userService
     */
    public function __construct(
        FlashBagInterface $flashBag,
        TokenStorageInterface $tokenStorage,
        SessionInterface $session,
        UserServiceInterface $userService
    ) {
        $this->flashBag = $flashBag;
        $this->tokenStorage = $tokenStorage;
        $this->session = $session;
        $this->userService = $userService;
    }

    /**
     * @Route("/verification/{vkey}", name="app_verification", methods={"GET"})
     *
     * @param User                   $user
     * @param TwigResponderInterface $responder
     *
     * @return RedirectResponse|Response*
     */
    public function __invoke(User $user, TwigResponderInterface $responder)
    {
        $verification = $this->userService->verification($user);

        if (false === $verification) {
            $this->flashBag->add('error', 'Votre compte est déjà vérifié.');

            return $responder('homepage');
        } else {
            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->tokenStorage->setToken($token);
            $this->session->set('_security_secured_area', serialize($token));

            return $responder('registration/registration-confirmed.html.twig');
        }
    }
}
