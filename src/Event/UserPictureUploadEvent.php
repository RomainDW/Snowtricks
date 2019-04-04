<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/19/19
 * Time: 7:19 PM.
 */

namespace App\Event;

use App\Domain\Entity\Interfaces\ImageInterface;
use App\Event\Interfaces\ImageEventInterface;
use Symfony\Component\EventDispatcher\Event;

class UserPictureUploadEvent extends Event implements ImageEventInterface
{
    public const NAME = 'picture.upload';

    protected $picture;

    public function __construct(ImageInterface $picture)
    {
        $this->picture = $picture;
    }

    public function getImage()
    {
        return $this->picture;
    }
}
