<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/30/19
 * Time: 6:35 PM.
 */

namespace App\Domain\Notifier\Interfaces;

use Swift_Mailer;
use Twig\Environment;

interface MailNotifierInterface
{
    public function __construct(Swift_Mailer $mailer, Environment $templating);
}
