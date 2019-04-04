<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/30/19
 * Time: 5:47 PM.
 */

namespace App\Action\Interfaces;

use App\Domain\Service\Interfaces\TrickServiceInterface;
use App\Handler\FormHandler\Interfaces\CreateTrickFormHandlerInterface;
use App\Responder\Interfaces\TwigResponderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

interface CreateTrickActionInterface
{
    public function __construct(FormFactoryInterface $formFactory, Security $security, CreateTrickFormHandlerInterface $formHandler, TrickServiceInterface $trickService);

    public function __invoke(Request $request, TwigResponderInterface $responder);
}
