<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/9/19
 * Time: 7:32 PM.
 */

namespace App\Domain\Notifier;

use App\Domain\Entity\Interfaces\UserInterface;
use App\Domain\Entity\User;
use App\Domain\Notifier\Interfaces\MailNotifierInterface;
use Swift_Mailer;
use Swift_Message;
use Twig\Environment;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;

class MailNotifier implements MailNotifierInterface
{
    private $mailer;
    private $templating;

    /**
     * MailNotifier constructor.
     *
     * @param Swift_Mailer $mailer
     * @param Environment  $templating
     */
    public function __construct(Swift_Mailer $mailer, Environment $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    /**
     * @param User   $user
     * @param string $from
     * @param string $to
     *
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function notifyResetPassword(User $user, string $from = 'noreply@snowtricks.com', $to = 'romain.ollier34@gmail.com')
    {
        $message = (new Swift_Message('Réinitialiser votre mot de passe.'))
            ->setFrom($from)
            ->setTo($to)
            ->setBody(
                $this->templating->render(
                    'emails/reset-password.html.twig',
                    ['vkey' => $user->getVkey()]
                ),
                'text/html'
            )
        ;

        $this->mailer->send($message);
    }

    /**
     * @param UserInterface $user
     * @param string        $from
     * @param string        $to
     *
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function notifyRegistration(UserInterface $user, string $from = 'noreply@snowtricks.com', $to = 'romain.ollier34@gmail.com')
    {
        $message = (new Swift_Message('Confirmation de création de compte'))
            ->setFrom($from)
            ->setTo($to)
            ->setBody(
                $this->templating->render(
                    'emails/registration.html.twig',
                    ['name' => $user->getUsername(), 'vkey' => $user->getVkey()]
                ),
                'text/html'
            )
        ;

        $this->mailer->send($message);
    }
}
