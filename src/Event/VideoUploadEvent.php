<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/22/19
 * Time: 5:10 PM.
 */

namespace App\Event;

use App\Domain\Entity\Video;
use Symfony\Component\EventDispatcher\Event;

class VideoUploadEvent extends Event
{
    public const NAME = 'video.upload';

    protected $video;

    /**
     * UploadVideoEvent constructor.
     *
     * @param $video
     */
    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    public function getVideo()
    {
        return $this->video;
    }
}
