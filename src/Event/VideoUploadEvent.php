<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/22/19
 * Time: 5:10 PM.
 */

namespace App\Event;

use App\Domain\Entity\Interfaces\VideoInterface;
use App\Event\Interfaces\VideoEventInterface;
use Symfony\Component\EventDispatcher\Event;

class VideoUploadEvent extends Event implements VideoEventInterface
{
    public const NAME = 'video.upload';

    protected $video;

    /**
     * UploadVideoEvent constructor.
     *
     * @param $video
     */
    public function __construct(VideoInterface $video)
    {
        $this->video = $video;
    }

    public function getVideo(): VideoInterface
    {
        return $this->video;
    }
}
