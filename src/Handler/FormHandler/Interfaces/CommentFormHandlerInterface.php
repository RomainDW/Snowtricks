<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 4/4/19
 * Time: 10:12 AM.
 */

namespace App\Handler\FormHandler\Interfaces;

use App\Domain\Entity\Interfaces\CommentInterface;
use App\Domain\Entity\Trick;
use App\Domain\Entity\User;
use App\Domain\Service\Interfaces\CommentServiceInterface;
use Symfony\Component\Form\FormInterface;

interface CommentFormHandlerInterface
{
    public function __construct(CommentServiceInterface $commentService);

    public function handle(FormInterface $form, CommentInterface $comment, User $user, Trick $trick): bool;
}
