<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/24/19
 * Time: 4:27 PM.
 */

namespace App\Event;

use App\Entity\ImageInterface;
use Symfony\Component\EventDispatcher\Event;

class UserPictureRemoveEvent extends Event implements ImageEventInterface
{
    public const NAME = 'picture.remove';

    protected $image;

    public function __construct(ImageInterface $image)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }
}
