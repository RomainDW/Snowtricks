<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/30/19
 * Time: 5:53 PM.
 */

namespace App\Action\Interfaces;

use App\Repository\TrickRepository;
use App\Responder\Interfaces\TwigResponderInterface;

interface HomeActionInterface
{
    public function __construct(TrickRepository $trickRepository);

    public function __invoke(TwigResponderInterface $responder);
}
