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
use App\Repository\CommentRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CommentFormHandler
{
    private $commentRepository;
    private $url_generator;
    private $flashBag;

    /**
     * CommentFormhandler constructor.
     *
     * @param CommentRepository     $commentRepository
     * @param UrlGeneratorInterface $url_generator
     * @param FlashBagInterface     $flashBag
     */
    public function __construct(CommentRepository $commentRepository, UrlGeneratorInterface $url_generator, FlashBagInterface $flashBag)
    {
        $this->commentRepository = $commentRepository;
        $this->url_generator = $url_generator;
        $this->flashBag = $flashBag;
    }

    /**
     * @param FormInterface $form
     * @param Comment       $comment
     * @param User          $user
     * @param Trick         $trick
     * @param $slug
     *
     * @return bool|RedirectResponse
     *
     * @throws \Exception
     */
    public function handle(FormInterface $form, Comment $comment, User $user, Trick $trick)
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new \DateTime());
            $comment->setUser($user);
            $comment->setTrick($trick);

            return true;
        }

        return false;
    }
}
