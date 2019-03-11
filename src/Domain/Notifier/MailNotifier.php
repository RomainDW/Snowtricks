<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/9/19
 * Time: 7:32 PM.
 */

namespace App\Domain\Notifier;

use App\Domain\Entity\User;
use Twig\Environment;

class MailNotifier
{
    private $mailer;
    private $templating;

    /**
     * MailNotifier constructor.
     *
     * @param \Swift_Mailer $mailer
     * @param Environment   $templating
     */
    public function __construct(\Swift_Mailer $mailer, Environment $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    /**
     * @param User   $user
     * @param string $from
     * @param string $to
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function notifyResetPassword(User $user, string $from = 'noreply@snowtricks.com', $to = 'romain.ollier34@gmail.com')
    {
        $message = (new \Swift_Message('Réinitialiser votre mot de passe.'))
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
     * @param User   $user
     * @param string $from
     * @param string $to
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function notifyRegistration(User $user, string $from = 'noreply@snowtricks.com', $to = 'romain.ollier34@gmail.com')
    {
        $message = (new \Swift_Message('Confirmation de création de compte'))
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
