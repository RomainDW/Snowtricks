<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/7/19
 * Time: 8:14 PM.
 */

namespace App\Action;

use App\Domain\Entity\User;
use App\Responder\RegistrationVerificationResponder;
use App\Domain\Service\UserService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class RegistrationVerificationAction
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
     * @var UserService
     */
    private $userService;

    /**
     * RegistrationVerificationAction constructor.
     *
     * @param FlashBagInterface     $flashBag
     * @param TokenStorageInterface $tokenStorage
     * @param SessionInterface      $session
     * @param UserService           $userService
     */
    public function __construct(
        FlashBagInterface $flashBag,
        TokenStorageInterface $tokenStorage,
        SessionInterface $session,
        UserService $userService
    ) {
        $this->flashBag = $flashBag;
        $this->tokenStorage = $tokenStorage;
        $this->session = $session;
        $this->userService = $userService;
    }

    /**
     * @Route("/verification/{vkey}", name="app_verification", methods={"GET"})
     *
     * @param User                              $user
     * @param RegistrationVerificationResponder $responder
     *
     * @return RedirectResponse|Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(User $user, RegistrationVerificationResponder $responder)
    {
        $verification = $this->userService->verification($user);

        if (false === $verification) {
            $this->flashBag->add('error', 'Votre compte est déjà vérifié.');

            return $responder('redirect-homepage');
        } else {
            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->tokenStorage->setToken($token);
            $this->session->set('_security_secured_area', serialize($token));

            return $responder();
        }
    }
}
