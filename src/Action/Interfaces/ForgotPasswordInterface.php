<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/30/19
 * Time: 5:52 PM.
 */

namespace App\Action\Interfaces;

use App\Handler\FormHandler\Interfaces\ForgotPasswordFormHandlerInterface;
use App\Responder\Interfaces\TwigResponderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

interface ForgotPasswordInterface
{
    public function __construct(FormFactoryInterface $formFactory, ForgotPasswordFormHandlerInterface $formHandler);

    public function __invoke(Request $request, TwigResponderInterface $responder);
}
