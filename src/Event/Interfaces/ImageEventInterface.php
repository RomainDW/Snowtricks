<?php

namespace App\Event\Interfaces;

use App\Domain\Entity\Interfaces\ImageInterface;

interface ImageEventInterface
{
    public function getImage();

    public function __construct(ImageInterface $image);
}
