<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/7/19
 * Time: 8:08 PM.
 */

namespace App\Action;

use App\Domain\Entity\User;
use App\Responder\MailSentResponder;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MailSentAction
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
     * @var MailSentResponder
     */
    private $responder;

    /**
     * MailSentAction constructor.
     *
     * @param FlashBagInterface     $flashBag
     * @param UrlGeneratorInterface $urlGenerator
     * @param MailSentResponder     $responder
     */
    public function __construct(FlashBagInterface $flashBag, UrlGeneratorInterface $urlGenerator, MailSentResponder $responder)
    {
        $this->flashBag = $flashBag;
        $this->urlGenerator = $urlGenerator;
        $this->responder = $responder;
    }

    /**
     * @Route("/register/{id}", name="app_mail_sent")
     *
     * @param User $user
     *
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(User $user)
    {
        $hasAccess = $user->hasRole('ROLE_USER_NOT_VERIFIED');

        if (!$hasAccess) {
            $this->flashBag->add('error', 'Votre compte est déjà vérifié.');

            return new RedirectResponse($this->urlGenerator->generate('homepage'));
        }

        $userEmail = $user->getEmail();

        $responder = $this->responder;

        return $responder(['userEmail' => $userEmail]);
    }
}
