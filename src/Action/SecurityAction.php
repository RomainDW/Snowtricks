<?php

namespace App\Action;

use App\Action\Interfaces\SecurityActionInterface;
use App\Responder\Interfaces\TwigResponderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityAction implements SecurityActionInterface
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
     * SecurityAction constructor.
     *
     * @param Security          $security
     * @param FlashBagInterface $flashBag
     */
    public function __construct(Security $security, FlashBagInterface $flashBag)
    {
        $this->security = $security;
        $this->flashBag = $flashBag;
    }

    /**
     * @Route("/login", name="app_login")
     *
     * @param AuthenticationUtils    $authenticationUtils
     * @param TwigResponderInterface $responder
     *
     * @return Response
     */
    public function __invoke(AuthenticationUtils $authenticationUtils, TwigResponderInterface $responder): Response
    {
        if ($this->security->isGranted('ROLE_USER')) {
            $this->flashBag->add('error', 'Vous êtes déjà connecté(e)');

            return $responder('homepage');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $responder('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }
}
