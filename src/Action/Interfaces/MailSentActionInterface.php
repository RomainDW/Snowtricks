<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/30/19
 * Time: 5:57 PM.
 */

namespace App\Action\Interfaces;

use App\Domain\Entity\User;
use App\Responder\Interfaces\TwigResponderInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

interface MailSentActionInterface
{
    public function __construct(FlashBagInterface $flashBag);

    public function __invoke(User $user, TwigResponderInterface $responder);
}
