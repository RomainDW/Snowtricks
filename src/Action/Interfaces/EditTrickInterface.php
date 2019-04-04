<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/30/19
 * Time: 5:51 PM.
 */

namespace App\Action\Interfaces;

use App\Domain\Entity\Trick;
use App\Handler\FormHandler\Interfaces\EditTrickFormHandlerInterface;
use App\Responder\Interfaces\TwigResponderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

interface EditTrickInterface
{
    public function __construct(FormFactoryInterface $formFactory, EditTrickFormHandlerInterface $formHandler);

    public function __invoke(Trick $trick, Request $request, TwigResponderInterface $responder);
}
