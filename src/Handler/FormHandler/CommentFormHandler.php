<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 3/4/19
 * Time: 8:07 PM.
 */

namespace App\Handler\FormHandler;

use App\Domain\Entity\Interfaces\CommentInterface;
use App\Domain\Entity\Trick;
use App\Domain\Entity\User;
use App\Domain\Service\Interfaces\CommentServiceInterface;
use App\Handler\FormHandler\Interfaces\CommentFormHandlerInterface;
use DateTime;
use Exception;
use Symfony\Component\Form\FormInterface;

class CommentFormHandler implements CommentFormHandlerInterface
{
    /**
     * @var CommentServiceInterface
     */
    private $commentService;

    /**
     * CommentFormHandler constructor.
     *
     * @param CommentServiceInterface $commentService
     */
    public function __construct(CommentServiceInterface $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * @param FormInterface    $form
     * @param CommentInterface $comment
     * @param User             $user
     * @param Trick            $trick
     *
     * @return bool
     *
     * @throws Exception
     */
    public function handle(FormInterface $form, CommentInterface $comment, User $user, Trick $trick): bool
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new DateTime());
            $comment->setUser($user);
            $comment->setTrick($trick);

            $this->commentService->save($comment);

            return true;
        }

        return false;
    }
}
