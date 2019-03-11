<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/7/19
 * Time: 8:14 PM.
 */

namespace App\Action;

use App\Domain\Entity\User;
use App\Responder\HomeRedirectResponder;
use App\Responder\RegistrationVerificationResponder;
use App\Service\AccountVerification;
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
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var RegistrationVerificationResponder
     */
    private $responder;
    /**
     * @var HomeRedirectResponder
     */
    private $homeRedirectResponder;

    /**
     * RegistrationVerificationAction constructor.
     *
     * @param FlashBagInterface                 $flashBag
     * @param UrlGeneratorInterface             $urlGenerator
     * @param TokenStorageInterface             $tokenStorage
     * @param SessionInterface                  $session
     * @param RegistrationVerificationResponder $responder
     * @param HomeRedirectResponder             $homeRedirectResponder
     */
    public function __construct(
        FlashBagInterface $flashBag,
        UrlGeneratorInterface $urlGenerator,
        TokenStorageInterface $tokenStorage,
        SessionInterface $session,
        RegistrationVerificationResponder $responder,
        HomeRedirectResponder $homeRedirectResponder
    ) {
        $this->flashBag = $flashBag;
        $this->urlGenerator = $urlGenerator;
        $this->tokenStorage = $tokenStorage;
        $this->session = $session;
        $this->responder = $responder;
        $this->homeRedirectResponder = $homeRedirectResponder;
    }

    /**
     * @Route("/verification/{vkey}", name="app_verification", methods={"GET"})
     *
     * @param User                $user
     * @param AccountVerification $accountVerification
     *
     * @return RedirectResponse|Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(User $user, AccountVerification $accountVerification)
    {
        $verification = $accountVerification->verification($user);

        if (false === $verification) {
            $this->flashBag->add('error', 'Votre compte est déjà vérifié.');
            $responder = $this->homeRedirectResponder;

            return $responder();

        } else {
            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->tokenStorage->setToken($token);
            $this->session->set('_security_secured_area', serialize($token));

            $responder = $this->responder;

            return $responder();
        }
    }
}
