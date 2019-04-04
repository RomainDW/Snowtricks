<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/30/19
 * Time: 6:01 PM.
 */

namespace App\Action\Interfaces;

use App\Responder\Interfaces\TwigResponderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

interface SecurityActionInterface
{
    public function __construct(Security $security, FlashBagInterface $flashBag);

    public function __invoke(AuthenticationUtils $authenticationUtils, TwigResponderInterface $responder): Response;
}
