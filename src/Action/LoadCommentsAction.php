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
use App\Service\SnowtrickConfig;
use Symfony\Component\Routing\Annotation\Route;

class LoadCommentsAction
{
    /**
     * @var CommentRepository
     */
    private $commentRepository;

    /**
     * @var LoadCommentsResponder
     */
    private $responder;

    /**
     * LoadCommentsAction constructor.
     *
     * @param CommentRepository     $commentRepository
     * @param LoadCommentsResponder $responder
     */
    public function __construct(CommentRepository $commentRepository, LoadCommentsResponder $responder)
    {
        $this->commentRepository = $commentRepository;
        $this->responder = $responder;
    }

    /**
     * @param Trick $trick
     * @param $offset
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @Route("/load-comments/{slug}/{offset}", name="loadComments", methods={"POST"})
     */
    public function __invoke(Trick $trick, $offset)
    {
        // Todo: utiliser le manager
        $comments = $this->commentRepository->getCommentsPagination($offset, SnowtrickConfig::getNumberOfCommentsDisplayed(), $trick);

        $responder = $this->responder;

        return $responder(['comments' => $comments]);
    }
}
