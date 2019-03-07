<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/23/19
 * Time: 4:00 PM.
 */

namespace App\Service;

use App\DTO\CreateTrickDTO;
use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\User;
use App\Entity\Video;
use App\Event\ImageRemoveEvent;
use App\Event\ImageUploadEvent;
use App\Event\VideoUploadEvent;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class TrickService
{
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param CreateTrickDTO $createTrickDTO
     * @param UserInterface  $user
     *
     * @return Trick
     *
     * @throws \Exception
     */
    public function InitTrick(CreateTrickDTO $createTrickDTO, UserInterface $user)
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

    /**
     * @param Trick          $trick
     * @param CreateTrickDTO $trickDTO
     *
     * @return CreateTrickDTO
     *
     * @throws \Exception
     */
    public function UpdateTrick(Trick $trick, CreateTrickDTO $trickDTO)
    {
        $trickDTO->updatedAt = new \DateTime();
        $trickDTO->slug = SlugService::slugify($trickDTO->title);

        $originalImages = new ArrayCollection();
        $originalVideos = new ArrayCollection();

        // Create an ArrayCollection of the current Image objects in the database
        foreach ($trick->getImages() as $image) {
            $originalImages->add($image);
        }

        foreach ($trick->getVideos() as $video) {
            $originalVideos->add($video);
        }

        foreach ($trickDTO->images as $image) {
            if ($image instanceof Image) {
                if (!empty($image->getFile() && !empty($image->getFileName()))) {
                    $imageRemoveEvent = new ImageRemoveEvent($image);
                    $this->dispatcher->dispatch(ImageRemoveEvent::NAME, $imageRemoveEvent);
                }
                if (!empty($image->getFile())) {
                    $image->setTrick($trick);
                    $imageUploadEvent = new ImageUploadEvent($image);
                    $this->dispatcher->dispatch(ImageUploadEvent::NAME, $imageUploadEvent);
                }
            }
        }

        foreach ($originalImages as $image) {
            // remove old images that has been removed
            if (false === in_array($image, $trickDTO->images)) {
                // remove the Image from the Trick
                $trick->getImages()->removeElement($image);
                $event = new ImageRemoveEvent($image);
                $this->dispatcher->dispatch(ImageRemoveEvent::NAME, $event);
            }
        }

        foreach ($trickDTO->videos as $video) {
            if ($video instanceof Video) {
                $video->setTrick($trick);
                $event = new VideoUploadEvent($video);
                $this->dispatcher->dispatch(VideoUploadEvent::NAME, $event);
            }
        }

        foreach ($originalVideos as $originalVideo) {
            if (false === in_array($originalVideo, $trickDTO->videos)) {
                $trick->getVideos()->removeElement($originalVideo);
            }
        }

        return $trickDTO;
    }
}
