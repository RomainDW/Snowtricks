<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/7/19
 * Time: 8:08 PM.
 */

namespace App\Action;

use App\Domain\Entity\User;
use App\Responder\Interfaces\TwigResponderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

class MailSentAction
{
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * MailSentAction constructor.
     *
     * @param FlashBagInterface $flashBag
     */
    public function __construct(FlashBagInterface $flashBag)
    {
        $this->flashBag = $flashBag;
    }

    /**
     * @Route("/register/{id}", name="app_mail_sent")
     *
     * @param User                   $user
     * @param TwigResponderInterface $responder
     *
     * @return Response
     */
    public function __invoke(User $user, TwigResponderInterface $responder)
    {
        $hasAccess = $user->hasRole('ROLE_USER_NOT_VERIFIED');

        if (!$hasAccess) {
            $this->flashBag->add('error', 'Votre compte est déjà vérifié.');

            return $responder('homepage');
        }

        $userEmail = $user->getEmail();

        return $responder('registration/registration-pre-validation.html.twig', ['userEmail' => $userEmail]);
    }
}
