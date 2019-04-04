<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/30/19
 * Time: 6:02 PM.
 */

namespace App\Action\Interfaces;

use App\Domain\Entity\Trick;
use App\Handler\FormHandler\Interfaces\CommentFormHandlerInterface;
use App\Repository\CommentRepository;
use App\Responder\Interfaces\TwigResponderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Security;

interface ShowTrickActionInterface
{
    public function __construct(
        Security $security,
        CommentRepository $commentRepository,
        FormFactoryInterface $formFactory,
        FlashBagInterface $flashBag,
        CommentFormHandlerInterface $formHandler
    );

    public function __invoke(Trick $trick, Request $request, TwigResponderInterface $responder);
}
