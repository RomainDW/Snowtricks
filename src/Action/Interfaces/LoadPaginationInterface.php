<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/30/19
 * Time: 5:56 PM.
 */

namespace App\Action\Interfaces;

use App\Repository\TrickRepository;
use App\Responder\Interfaces\TwigResponderInterface;

interface LoadPaginationInterface
{
    public function __construct(TrickRepository $trickRepository);

    public function __invoke($offset, TwigResponderInterface $responder);
}
