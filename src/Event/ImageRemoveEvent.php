<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/8/19
 * Time: 3:21 PM.
 */

namespace App\Event;

use App\Entity\Image;
use Symfony\Component\EventDispatcher\Event;

class ImageRemoveEvent extends Event
{
    public const NAME = 'image.remove';

    protected $image;

    public function __construct(Image $image)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }
}
