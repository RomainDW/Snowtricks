<?php

namespace App\Action;

use App\Responder\HomeRedirectResponder;
use App\Responder\SecurityResponder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityAction
{
    /**
     * @var Security
     */
    private $security;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var SecurityResponder
     */
    private $responder;
    /**
     * @var HomeRedirectResponder
     */
    private $homeRedirectResponder;

    /**
     * SecurityAction constructor.
     *
     * @param Security              $security
     * @param FlashBagInterface     $flashBag
     * @param SecurityResponder     $responder
     * @param HomeRedirectResponder $homeRedirectResponder
     */
    public function __construct(Security $security, FlashBagInterface $flashBag, SecurityResponder $responder, HomeRedirectResponder $homeRedirectResponder)
    {
        $this->security = $security;
        $this->flashBag = $flashBag;
        $this->responder = $responder;
        $this->homeRedirectResponder = $homeRedirectResponder;
    }

    /**
     * @Route("/login", name="app_login")
     *
     * @param AuthenticationUtils $authenticationUtils
     *
     * @return Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->security->isGranted('ROLE_USER')) {
            $this->flashBag->add('error', 'Vous êtes déjà connecté(e)');
            $responder = $this->homeRedirectResponder;

            return $responder();
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $responder = $this->responder;

        return $responder(['last_username' => $lastUsername, 'error' => $error]);
    }
}
