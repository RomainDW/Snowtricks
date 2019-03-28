<?php

namespace App\Event;

use App\Domain\Entity\ImageInterface;

interface ImageEventInterface
{
    public function getImage();

    public function __construct(ImageInterface $image);
}
