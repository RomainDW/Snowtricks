<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 3/4/19
 * Time: 8:07 PM.
 */

namespace App\Handler\FormHandler;

use App\Domain\Entity\Comment;
use App\Domain\Entity\Trick;
use App\Domain\Entity\User;
use App\Domain\Exception\ValidationException;
use App\Domain\Service\CommentService;
use DateTime;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CommentFormHandler
{
    /**
     * @var CommentService
     */
    private $commentService;

    /**
     * CommentFormhandler constructor.
     *
     * @param CommentService $commentService
     */
    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * @param FormInterface $form
     * @param Comment       $comment
     * @param User          $user
     * @param Trick         $trick
     *
     * @return bool|RedirectResponse
     *
     * @throws ValidationException
     */
    public function handle(FormInterface $form, Comment $comment, User $user, Trick $trick)
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
