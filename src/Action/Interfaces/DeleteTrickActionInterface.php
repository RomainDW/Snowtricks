<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/30/19
 * Time: 5:49 PM.
 */

namespace App\Action\Interfaces;

use App\Domain\Entity\Trick;
use App\Domain\Service\Interfaces\TrickServiceInterface;
use App\Responder\Interfaces\TwigResponderInterface;

interface DeleteTrickActionInterface
{
    public function __construct(TrickServiceInterface $trickService);

    public function __invoke(Trick $trick, TwigResponderInterface $responder);
}
