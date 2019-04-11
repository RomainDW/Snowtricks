<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/7/19
 * Time: 8:57 PM.
 */

namespace App\Action;

use App\Action\Interfaces\LoadCommentsInterface;
use App\Domain\Entity\Trick;
use App\Repository\CommentRepository;
use App\Responder\Interfaces\TwigResponderInterface;
use App\Utils\SnowtrickConfig;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoadCommentsAction implements LoadCommentsInterface
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
     * @param TwigResponderInterface $responder
     *
     * @return Response
     *
     * @Route("/charger-commentaires/{slug}/{offset}", name="loadComments", methods={"POST"})
     */
    public function __invoke(Trick $trick, $offset, TwigResponderInterface $responder)
    {
        $comments = $this->commentRepository->getCommentsPagination($offset, SnowtrickConfig::getNumberOfCommentsDisplayed(), $trick);

        return $responder('trick/_partials/ajax-comments.html.twig', ['comments' => $comments]);
    }
}
