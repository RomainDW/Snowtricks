<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/30/19
 * Time: 5:44 PM.
 */

namespace App\Action\Interfaces;

use App\Handler\FormHandler\Interfaces\AccountFormHandlerInterface;
use App\Responder\Interfaces\TwigResponderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

interface AccountActionInterface
{
    public function __construct(FormFactoryInterface $formFactory, Security $security, AccountFormHandlerInterface $formHandler);

    public function __invoke(Request $request, TwigResponderInterface $responder);
}
