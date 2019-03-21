<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/7/19
 * Time: 8:57 PM.
 */

namespace App\Action;

use App\Domain\Entity\Trick;
use App\Repository\CommentRepository;
use App\Responder\LoadCommentsResponder;
use App\Utils\SnowtrickConfig;
use Symfony\Component\Routing\Annotation\Route;

class LoadCommentsAction
{
    /**
     * @var CommentRepository
     */
    private $commentRepository;

    /**
     * LoadCommentsAction constructor.
     *
     * @param CommentRepository $commentRepository
     */
    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * @param Trick $trick
     * @param $offset
     *
     * @param LoadCommentsResponder $responder
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @Route("/load-comments/{slug}/{offset}", name="loadComments", methods={"POST"})
     */
    public function __invoke(Trick $trick, $offset, LoadCommentsResponder $responder)
    {
        $comments = $this->commentRepository->getCommentsPagination($offset, SnowtrickConfig::getNumberOfCommentsDisplayed(), $trick);

        return $responder(['comments' => $comments]);
    }
}
