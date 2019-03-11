<?php

namespace App\Event;

use App\Domain\Entity\Trick;
use Symfony\Component\EventDispatcher\Event;

class CreateTrickEvent extends Event
{
    const NAME = 'createTrickEvent';

    private $trick;

    public function __construct(Trick $trick)
    {
        $this->trick = $trick;
    }

    public function getTrick()
    {
        return $this->trick;
    }
}
