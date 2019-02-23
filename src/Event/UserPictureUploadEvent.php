<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/19/19
 * Time: 7:19 PM.
 */

namespace App\Event;

use App\DTO\PictureDTO;
use Symfony\Component\EventDispatcher\Event;

class UserPictureUploadEvent extends Event implements ImageUploadInterface
{
    public const NAME = 'user_picture.upload';

    protected $picture;

    public function __construct(PictureDTO $picture)
    {
        $this->picture = $picture;
    }

    public function getImage()
    {
        return $this->picture;
    }
}
