<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/30/19
 * Time: 5:54 PM.
 */

namespace App\Action\Interfaces;

use App\Domain\Entity\Trick;
use App\Repository\CommentRepository;
use App\Responder\Interfaces\TwigResponderInterface;

interface LoadCommentsInterface
{
    public function __construct(CommentRepository $commentRepository);

    public function __invoke(Trick $trick, $offset, TwigResponderInterface $responder);
}
