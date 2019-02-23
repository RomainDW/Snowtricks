<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/23/19
 * Time: 4:00 PM.
 */

namespace App\Service;

use App\DTO\CreateTrickDTO;
use App\Entity\Trick;
use App\Entity\User;
use App\Event\ImageUploadEvent;
use App\Event\VideoUploadEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class TrickService
{
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param CreateTrickDTO $createTrickDTO
     * @param User           $user
     *
     * @return Trick
     *
     * @throws \Exception
     */
    public function InitTrick(CreateTrickDTO $createTrickDTO, User $user)
    {
        $createTrickDTO->createdAt = new \DateTime();

        $createTrickDTO->user = $user;
        $createTrickDTO->slug = SlugService::slugify($createTrickDTO->title);

        $trick = new Trick($createTrickDTO);

        foreach ($trick->getImages() as $image) {
            $image->setTrick($trick);
            $event = new ImageUploadEvent($image);
            $this->dispatcher->dispatch(ImageUploadEvent::NAME, $event);
        }

        foreach ($trick->getVideos() as $video) {
            $video->setTrick($trick);
            $event = new VideoUploadEvent($video);
            $this->dispatcher->dispatch(VideoUploadEvent::NAME, $event);
        }

        return $trick;
    }
}
